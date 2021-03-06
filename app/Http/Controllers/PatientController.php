<?php

namespace App\Http\Controllers;

use Edujugon\PushNotification\PushNotification;
use App\Allergy;
use App\Http\Requests\PatientRequest;
use App\Pressure;
use App\Repositories\PatientRepository;
use App\Repositories\AppointmentRepository;
use App\Sugar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;
use App\Patient;
use Illuminate\Support\Facades\Log;

class PatientController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PatientRepository $patientRepo, AppointmentRepository $appointmentRepo)
    {
        $this->middleware('auth');
        $this->patientRepo = $patientRepo;
        $this->appointmentRepo = $appointmentRepo;
    }

    /**
     * Guardar paciente
     */
    public function create()
    {
        $user = auth()->user();

        return view('user.register')->with('user');
    }

    /**
     * Guardar paciente
     */
    public function register(PatientRequest $request)
    {
        $this->validate(request(), [ //se valida que no exista en user el correo q quiere cambiar
                'phone' => ['required', Rule::unique('users')->ignore(auth()->id())],
                'email' => ['email', Rule::unique('users')->ignore(auth()->id())]
        ]);

        $patient = $this->patientRepo->store($request->all());

        return Redirect('/');
    }

    /**
     * Guardar paciente
     */
    public function store(PatientRequest $request)
    {
        $this->validate(request(), [ //se valida que no exista en user el correo q quiere cambiar
                'phone' => ['required', Rule::unique('users')->ignore(auth()->id())],
                'email' => ['email', Rule::unique('users')->ignore(auth()->id())]
        ]);

        $patient = $this->patientRepo->store($request->all());

        return $patient;
    }

    /**
     * Actualizar Paciente
     */
    public function update($id)
    {
        $this->validate(request(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'address' => 'required',
                'province' => 'required',
                'city' => 'required',
                'phone' => ['required', Rule::unique('patients')->ignore($id)],
                'email' => ['email', Rule::unique('patients')->ignore($id)]//'required|email|max:255|unique:patients',
        ]);

        $this->validate(request(), [ //se valida que no exista en user el correo q quiere cambiar
                'phone' => ['required', Rule::unique('users')->ignore(auth()->id())],
                'email' => ['email', Rule::unique('users')->ignore(auth()->id())]
        ]);

        $patient = $this->patientRepo->update($id, request()->all());

        return $patient;
    }

    /**
    * Actualizar Paciente
    */
    public function expedient($id)
    {
        $patient = $this->patientRepo->findById($id);

        if (!auth()->user()->hasPatient($patient->id)) { // verifica que el paciente es del usuario logueado
            return redirect('/');
        }

        return view('user.expedient', compact('patient'));
    }

    /**
    * Agregar medicamentos a pacientes
    */
    public function medicines($id)
    {
        $medicine = $this->patientRepo->addMedicine($id, request()->all());

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
    public function allergies($id)
    {
        $patient = $this->patientRepo->findById($id);
        $history = $patient->history;

        $data = request()->all();

        $data['user_id'] = auth()->id();

        $data['history_id'] = $history->id;

        $allergy = Allergy::create($data);
        $allergy->load('user.roles');

        return $allergy;
    }

    /**
     * Eliminar medicamentos a pacientes
     */
    public function deleteAllergies($id)
    {
        $allergy = Allergy::findOrFail($id)->delete($id);

        return '';
    }

    /**
     * Agregar medicamentos a pacientes
     */
    public function pressures($id)
    {
        $this->validate(request(), [
                'ps' => 'required|numeric',
                'pd' => 'required|numeric',
                'date_control' => 'required',
                'time_control' => 'required'
            ]);

        $patient = $this->patientRepo->findById($id);
        $pressures = $patient->pressures()->create(request()->all());

        return $pressures;
    }

    /**
     * Eliminar medicamentos a pacientes
     */
    public function deletePressures($id)
    {
        $allergy = Pressure::findOrFail($id)->delete($id);

        return '';
    }

    /**
     * Agregar medicamentos a pacientes
     */
    public function sugars($id)
    {
        $this->validate(request(), [
                'glicemia' => 'required|numeric',
                'date_control' => 'required',
                'time_control' => 'required'
            ]);

        $patient = $this->patientRepo->findById($id);
        $sugars = $patient->sugars()->create(request()->all());

        return $sugars;
    }

    /**
     * Eliminar medicamentos a pacientes
     */
    public function deleteSugars($id)
    {
        $sugar = Sugar::findOrFail($id)->delete($id);

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

    public function marketing()
    {
        $emailsUsers = [];
        $tokensUsers = [];
        $title = request('title') ? request('title') : 'Nueva Información!!';
        $body = request('body') ? request('body') : 'Te ha llegado una nueva notificacion de informacion de interes, revisala en el panel de notificaciones !!';

        $mimes = ['jpg', 'jpeg', 'bmp', 'png'];
        $fileUploaded = '';

        if (request()->file('file')) {
            $file = request()->file('file');
            $name = $file->getClientOriginalName();
            $ext = $file->guessClientExtension();
            $onlyName = str_slug(pathinfo($name)['filename'], '-');

            if (in_array($ext, $mimes)) {
                $fileUploaded = $file->storeAs('marketing/' . auth()->id() , uniqid() . '.' . $ext, 'public');
            }
        }

        $patients = Patient::with(['user' => function ($query) {
            $query->whereHas('roles', function ($q) {
                $q->where('name', 'paciente');
            });
        }])->whereIn('id', request('patients'))->get();

        foreach ($patients as $patient) {
            $user = $patient->user->first();

            if ($user && $user->push_token) {
                $tokensUsers[] = $user->push_token;

                $notificationItem = [
                                'user_id' => $user->id,
                                'title' => $title,
                                'body' => $body,
                                'media' => $fileUploaded,
                            ];
                // $notificationsList[] = $notificationItem;

                $user->appNotifications()->create($notificationItem);
            }
        }

        if (count($tokensUsers)) {
            $push = new PushNotification('fcm');

            $response = $push->setMessage([
                            'notification' => [
                                'title' => $title,
                                'body' => $body,
                                'sound' => 'default'
                            ],
                            'data' => [
                                'tipo' => 'marketing',
                                'title' => $title,
                                'body' => $body,
                                'media' => $fileUploaded,
                            ]
                        ])->setApiKey(env('API_WEB_KEY_FIREBASE_PATIENTS'))
                            ->setDevicesToken($tokensUsers)
                            ->send()
                            ->getFeedback();

            Log::info('Mensaje Push code: ' . $response->success);
        }

        flash('Anuncio enviado', 'success');

        return back();
    }
}
