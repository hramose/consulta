<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Http\Requests\PatientRequest;
use App\Repositories\AppointmentRepository;
use App\Repositories\PatientRepository;
use App\Repositories\UserRepository;
use App\Mail\NewPatient;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Psugar;
use App\Ppressure;
use App\Pmedicine;

class PatientController extends Controller
{
    public function __construct(PatientRepository $patientRepo, AppointmentRepository $appointmentRepo, UserRepository $userRepo)
    {
        $this->middleware('authByRole:farmacia')->except('list', 'verifyIsPatient', 'addToYourPatients');
        $this->middleware('auth')->only('list', 'verifyIsPatient', 'addToYourPatients');
        $this->patientRepo = $patientRepo;
        $this->appointmentRepo = $appointmentRepo;
        $this->userRepo = $userRepo;
    }

    /**
     * Mostrar lista de pacientes de un doctor
     */
    public function index()
    {
        $search['q'] = request('q');
        $search['province'] = request('province');

        $inita = null;

        if (request('p')) {
            $p = Patient::find(request('p'));
        }

        if (request('inita')) {
            $inita = 1;
        }

        $pharmacy = auth()->user()->pharmacies->first();

        $patients = $this->patientRepo->findAllOfPharmacy($pharmacy, $search);

        return view('pharmacy.patients.index', compact('patients', 'search', 'inita'));
    }

    /**
     * Mostrar vista crear paciente
     */
    public function create()
    {
        return view('pharmacy.patients.create');
    }

    /**
     * Guardar paciente
     */
    public function store(PatientRequest $request)
    {
        $patient = $this->patientRepo->store($request->all());

        $data = $request->all();

        if (isset($data['email']) && $data['email']) {
            $this->validate(request(), [
                    'phone' => 'required|unique:users',
                    'email' => 'required|email|max:255|unique:users'
                ]);

            $data['password'] = (isset($data['password']) && $data['password']) ? $data['password'] : $data['phone'];

            $data['name'] = $data['first_name'];
            $data['provider'] = 'email';
            $data['provider_id'] = $data['email'];
            $data['role'] = Role::whereName('paciente')->first();
            $data['api_token'] = str_random(50);

            $user = $this->userRepo->store($data);
            $user_patient = $user->patients()->save($patient);

            try {
                \Mail::to($user)->send(new NewPatient($user));
            } catch (\Swift_TransportException $e) {  //Swift_RfcComplianceException
                \Log::error($e->getMessage());
            }
        } else {
            $this->validate(request(), [
                'phone' => 'required|unique:users',
            ]);

            $data['password'] = (isset($data['password']) && $data['password']) ? $data['password'] : $data['phone'];

            $data['name'] = $data['first_name'];
            $data['provider'] = 'phone';
            $data['provider_id'] = $data['phone'];
            $data['role'] = Role::whereName('paciente')->first();
            $data['api_token'] = str_random(50);

            $user = $this->userRepo->store($data);
            $user_patient = $user->patients()->save($patient);
        }

        flash('Paciente Creado', 'success');

        return Redirect('/pharmacy/patients/' . $patient->id . '/edit');
    }

    /**
     * Mostrar vista editar paciente
     */
    public function edit($id)
    {
        $patient = $this->patientRepo->findById($id);
        $pharmacy = auth()->user()->pharmacies->first();
        $search['pharmacy'] = $pharmacy->id;

        $search['status'] = 1;
        $initAppointments = $this->appointmentRepo->findAllByPatient($id, $search);

        $search['status'] = 0;
        $scheduledAppointments = $this->appointmentRepo->findAllByPatient($id, $search);

        $files = Storage::disk('public')->files('patients/' . $id . '/files');

        return view('pharmacy.patients.edit', compact('patient', 'files', 'initAppointments', 'scheduledAppointments'));
    }

    /**
     * Actualizar Paciente
     */
    public function update($id)
    {
        $this->validate(request(), [
                'first_name' => 'required',
                'last_name' => 'required',
                //'address' => 'required',
                'province' => 'required',
                //'city' => 'required',
                'phone' => ['required', Rule::unique('patients')->ignore($id)],
                'email' => ['email', Rule::unique('patients')->ignore($id)]//'required|email|max:255|unique:patients',
        ]);

        $patient = $this->patientRepo->findById($id);

        $user_patient = $patient->user()->whereHas('roles', function ($query) {
            $query->where('name', 'paciente');
        })->first();

        if ($user_patient) {
            $this->validate(request(), [ //se valida que no exista en user el correo q quiere cambiar
                    'phone' => ['required', Rule::unique('users')->ignore($user_patient->id)],
                    'email' => ['email', Rule::unique('users')->ignore($user_patient->id)]
            ]);
        } else {
            $this->validate(request(), [ //se valida que no exista en user el correo q quiere cambiar
                    'phone' => ['required', Rule::unique('users')],
                    'email' => ['email', Rule::unique('users')]
            ]);
        }

        $patient = $this->patientRepo->update($id, request()->all());

        flash('Paciente Actualizado', 'success');

        return back();
    }

    /**
    * Eliminar consulta(cita)
    */
    public function destroy($id)
    {
        $patient = $this->patientRepo->delete($id);

        ($patient === true) ? flash('Paciente eliminado correctamente!', 'success') : flash('No se puede eliminar paciente por que tiene citas asignadas', 'error');

        return back();
    }

    /**
     * Guardar foto de paciente
     */
    public function photos()
    {
        $mimes = ['jpg', 'jpeg', 'bmp', 'png'];
        $fileUploaded = 'error';

        if (request('patient_id') && request()->file('photo')) {
            $file = request()->file('photo');

            $ext = $file->guessClientExtension();

            if (in_array($ext, $mimes)) {
                $fileUploaded = $file->storeAs('patients/' . request('patient_id'), 'photo.jpg', 'public');
            }
        }

        return $fileUploaded;
    }

    /**
     * Guardar archivos de paciente
     */
    public function files()
    {
        $mimes = ['jpg', 'jpeg', 'bmp', 'png', 'pdf', 'doc', 'docx'];
        $fileUploaded = 'error';

        if (request('patient_id') && request()->file('file')) {
            $file = request()->file('file');

            $name = $file->getClientOriginalName();

            $ext = $file->guessClientExtension();

            if (in_array($ext, $mimes)) {
                $fileUploaded = $file->storeAs('patients/' . request('patient_id') . '/files', $name, 'public');
            }
        }

        return $fileUploaded;
    }

    /**
     * Eliminar archivos de pacientes
     */
    public function deleteFiles()
    {
        Storage::disk('public')->delete(request('file'));

        return '';
    }

    /**
     * Guardar historial medico de paciente
     */
    public function history($id)
    {
        $history = $this->patientRepo->updateHistory($id, request('data'));

        return 'Historial Medico guardado';
    }

    /**
     * Agregar medicamentos a pacientes
     */
    public function medicines($patient_id)
    {
        $this->validate(request(), [
            'name' => 'required',
            'date_purchase' => 'required',
        ]);

        $patient = $this->patientRepo->findById($patient_id);
        $medicine = $patient->pmedicines()->create(request()->all());

        return  $medicine;
    }

    /**
     * Eliminar medicamentos a pacientes
     */
    public function deleteMedicines($patient_id, $id)
    {
        $medicine = Pmedicine::findOrFail($id)->delete();

        return '';
    }

    /**
     * Agregar medicamentos a pacientes
     */
    public function pressures($patient_id)
    {
        $this->validate(request(), [
            'ps' => 'required|numeric',
            'pd' => 'required|numeric',
            'date_control' => 'required',
            'time_control' => 'required'
        ]);

        $patient = $this->patientRepo->findById($patient_id);
        $pressures = $patient->ppressures()->create(request()->all());

        return $pressures;
    }

    /**
     * Eliminar medicamentos a pacientes
     */
    public function deletePressures($patient_id, $id)
    {
        $pressure = Ppressure::findOrFail($id)->delete();

        return '';
    }

    /**
     * Agregar medicamentos a pacientes
     */
    public function sugars($patient_id)
    {
        $this->validate(request(), [
            'glicemia' => 'required|numeric',
            'date_control' => 'required',
            'time_control' => 'required'
        ]);

        $patient = $this->patientRepo->findById($patient_id);
        $sugars = $patient->psugars()->create(request()->all());

        return $sugars;
    }

    /**
     * Eliminar medicamentos a pacientes
     */
    public function deleteSugars($patient_id, $id)
    {
        $sugar = Psugar::findOrFail($id)->delete();

        return '';
    }
}
