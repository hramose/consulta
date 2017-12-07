<?php

namespace App\Http\Controllers\Medic;

use App\Http\Controllers\Controller;
use App\Http\Requests\PatientRequest;
use App\Mail\NewPatient;
use App\Repositories\AppointmentRepository;
use App\Repositories\PatientRepository;
use App\Repositories\UserRepository;
use App\Role;
use App\Appointment;
use App\Labresult;
use App\Labexam;
use App\Archivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PatientController extends Controller
{
    public function __construct(PatientRepository $patientRepo, AppointmentRepository $appointmentRepo, UserRepository $userRepo)
    {
        $this->middleware('auth');
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
        $inita = null;

        if (request('p')) {
            $p = Patient::find(request('p'));
        }

        if (request('inita')) {
            $inita = 1;
        }

        $patients = $this->patientRepo->findAll($search);

        return view('medic.patients.index', compact('patients', 'search', 'inita'));
    }

    /**
     * Mostrar vista crear paciente
     */
    public function create()
    {
        $clinic_id = request('clinic');

        return view('medic.patients.create', compact('clinic_id'));
    }

    /**
     * Guardar paciente
     */
    public function store(PatientRequest $request)
    {
        $data = $request->all();

        $patient = $this->patientRepo->store($request->all());

        //validamos que en users no hay email que va a registrase como paciente
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

        return Redirect('/medic/patients/' . $patient->id . '/edit');
    }

    /**
     * Mostrar vista editar paciente
     */
    public function edit($id)
    {
        $tab = request('tab');

        $patient = $this->patientRepo->findById($id);

        //$appointments = $this->appointmentRepo->findAllByPatient($id);

        $search['status'] = 1;
        $search['order'] = 'start';
        $initAppointments = $this->appointmentRepo->findAllByPatient($id, $search);

        $search['status'] = 0;
        $scheduledAppointments = $this->appointmentRepo->findAllByPatient($id, $search);

        $summaryAppointments = $initAppointments->take(3);

        // $initAppointments = $appointments->filter(function ($item, $key) {
        //         return $item->status > 0;
        //     });

        // $scheduledAppointments = $appointments->filter(function ($item, $key) {
        //         return $item->status == 0;
        //     });

        $appointments = [];//$patient->appointments->load('user', 'diagnostics'); // se comento por que es informacion repetida(diagnosticos) en las tres ultimas consultas

        //dd($appointments);

        $files = $patient->archivos; //Storage::disk('public')->files("patients/". $id ."/files");

        return view('medic.patients.edit', compact('patient', 'files', 'initAppointments', 'scheduledAppointments', 'tab', 'appointments', 'summaryAppointments'));
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
                'phone' => ['required',Rule::unique('patients')->ignore($id)],
                'email' => ['email', Rule::unique('patients')->ignore($id)]//'required|email|max:255|unique:patients',
        ]);

        $patient = $this->patientRepo->findById($id);
        
        $user_patient = $patient->user()->whereHas('roles', function ($query){
                        $query->where('name',  'paciente');
                    })->first();

        if($user_patient){

            $this->validate(request(),[ //se valida que no exista en user el correo q quiere cambiar
                    'phone' => ['required', Rule::unique('users')->ignore($user_patient->id)],
                    'email' => ['email', Rule::unique('users')->ignore($user_patient->id)]
            ]);
        }else{
            $this->validate(request(),[ //se valida que no exista en user el correo q quiere cambiar
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
        $this->validate(request(), [
            'file' => 'required|mimes:jpeg,bmp,png,pdf',
        ]);

        $data = request()->all();

        $mimes = ['jpg', 'jpeg', 'bmp', 'png', 'pdf'];
        $fileUploaded = 'error';

        if (request()->file('file')) {
            $file = request()->file('file');
            $name = $file->getClientOriginalName();
            $ext = $file->guessClientExtension();
            $onlyName = str_slug(pathinfo($name)['filename'], '-');

            if (in_array($ext, $mimes)) {
                $archivo = Archivo::create($data);

                $fileUploaded = $file->storeAs('patients/' . request('patient_id') . '/files', $archivo->id . '-' . $onlyName . '.' . $ext, 'public');

                $archivo->name = $archivo->id . '-' . $onlyName . '.' . $ext;
                $archivo->save();

                $result = [
                    'id' => $archivo->id,
                    'file' => $fileUploaded
                ];

                return $result;
            }
        }

        return $fileUploaded;

        // $mimes = ['jpg','jpeg','bmp','png','pdf','doc','docx'];
        // $fileUploaded = "error";

        // if(request('patient_id') && request()->file('file'))
        // {
        //     $file = request()->file('file');

        //     $name = $file->getClientOriginalName();

        //     $ext = $file->guessClientExtension();

        //     if(in_array($ext, $mimes))
        //         $fileUploaded = $file->storeAs("patients/". request('patient_id')."/files", $name,'public');
        // }

        // return $fileUploaded;
    }

    /**
     * Eliminar archivos de pacientes
     */
    public function deleteFiles()
    {
        Storage::disk('public')->delete(request('file'));

        $archivo = Archivo::find(request('id'));
        $archivo->delete();

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
    public function medicines($id)
    {
        $data = request()->all();

        if (auth()->user()->hasRole('medico')) {
            $data['medic_id'] = auth()->id();
        }

        $medicine = $this->patientRepo->addMedicine($id, $data);

        return  $medicine;
    }

    /**
     * Eliminar medicamentos a pacientes
     */
    public function deleteMedicines($id)
    {
        $medicine = $this->patientRepo->deleteMedicine($id);

        return '';
    }

    /**
     * Agregar medicamentos a pacientes
     */
    public function getLabExams($id)
    {
        if (request('appointment_id')) {
            $appointment = $this->appointmentRepo->findById(request('appointment_id'));

            $labexams = $appointment->labexams()->where('patient_id', $id)->get();
        } else {
            $patient = $this->patientRepo->findById($id);
            $labexams = $patient->labexams()->get();
        }

        $labexams = $labexams->groupBy(function ($exam) {
            return $exam->date;
        })->toArray();

        $data = [];
        $exam = [];

        foreach ($labexams as $key => $value) {
            $exam['date'] = $key;
            $exam['exams'] = $value;
            $data[] = $exam;
        }

        return  $data;
    }

    /**
    * Agregar medicamentos a pacientes
    */
    public function labExams($id)
    {
        $this->validate(request(), [
                'date' => 'required',
                'name' => 'required',
        ]);
        $data['date'] = request('date');
        $data['name'] = request('name');
        $data['patient_id'] = $id;

        $labexam = Labexam::create($data);

        $labexam->appointments()->attach(request('appointment_id')); // asociar la cita con el paciente

        return $labexam;
    }

    /**
    * Eliminar medicamentos a pacientes
    */
    public function deleteLabExams($id)
    {
        $exam = Labexam::find($id);
        $appointment = Appointment::find(request('appointment_id'));
        //dd($exam->appointments()->where('appointments.id','<>', request('appointment_id'))->count());
        if (!$exam->appointments()->where('appointments.id', '<>', request('appointment_id'))->count()) {
            $exam->delete();

            return '';
        }

        $patient = $appointment->labexams()->detach($id);

        return '';
    }

    /**
    * Agregar medicamentos a pacientes
    */
    public function labResults($id)
    {
        $this->validate(request(), [
                'date' => 'required',
                'file' => 'required|mimes:jpeg,bmp,png,pdf,xls,xlsx,doc,docx',
        ]);
        $data = request()->all();
        $data['patient_id'] = $id;

        $mimes = ['jpg', 'jpeg', 'bmp', 'png', 'pdf', 'xls', 'xlsx', 'doc', 'docx'];
        $fileUploaded = 'error';

        if (request()->file('file')) {
            $file = request()->file('file');
            $name = $file->getClientOriginalName();
            $ext = $file->guessClientExtension();
            $onlyName = str_slug(pathinfo($name)['filename'], '-');

            if (in_array($ext, $mimes)) {
                $labresult = Labresult::create($data);

                $fileUploaded = $file->storeAs('patients/' . $id . '/labresults/' . $labresult->id, $onlyName . '.' . $ext, 'public');

                $labresult->name = $onlyName . '.' . $ext;
                $labresult->save();

                return $labresult;
            }
        }

        return $fileUploaded;
    }

    /**
     * Eliminar medicamentos a pacientes
     */
    public function deleteLabResults($id)
    {
        $result = Labresult::find($id);
        $result->delete();

        Storage::disk('public')->delete(request('file'));

        return '';
    }

    /**
     * Mostrar lista de pacientes para el formulario de consultas(citas)
     */
    public function list()
    {
        $patients = $this->patientRepo->list(request('q'));

        return $patients;
    }

    /**
     * Guardar paciente
     */
    public function addToYourPatients($id)
    {
        $id_patient_confirm = request('id_patient_confirm');

        if ($id != $id_patient_confirm) {
            flash('Id de confirmacion incorrecto', 'danger');

            return back();
        }

        $patient = auth()->user()->patients()->attach($id);

        flash('Paciente Agregado a tu lista', 'success');

        return Redirect()->back();
    }
}
