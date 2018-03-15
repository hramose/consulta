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
use App\Mail\NewMarketing;

class PatientController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PatientRepository $patientRepo, AppointmentRepository $appointmentRepo )
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
        $this->validate(request(),[ //se valida que no exista en user el correo q quiere cambiar
                'phone' => ['required', Rule::unique('users')->ignore(auth()->id())],
                'email' => ['email', Rule::unique('users')->ignore(auth()->id())]
        ]);
        
        $patient =$this->patientRepo->store($request->all());



       return Redirect('/');

    }

    /**
     * Guardar paciente
     */
    public function store(PatientRequest $request)
    {
        $this->validate(request(),[ //se valida que no exista en user el correo q quiere cambiar
                'phone' => ['required', Rule::unique('users')->ignore(auth()->id())],
                'email' => ['email', Rule::unique('users')->ignore(auth()->id())]
        ]);
        
        $patient =$this->patientRepo->store($request->all());



        return $patient;

    }

    /**
     * Actualizar Paciente
     */
    public function update($id)
    {
       
         $this->validate(request(),[
                'first_name' => 'required',
                'last_name' => 'required',
                'address' => 'required',  
                'province' => 'required',  
                'city' => 'required', 
                'phone' => ['required', Rule::unique('patients')->ignore($id) ],
                'email' => ['email', Rule::unique('patients')->ignore($id) ]//'required|email|max:255|unique:patients',   
        ]);

         $this->validate(request(),[ //se valida que no exista en user el correo q quiere cambiar
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

        if(!auth()->user()->hasPatient($patient->id)) // verifica que el paciente es del usuario logueado
            return redirect('/');
        

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
        $this->validate(request(),[
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
        $this->validate(request(),[
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
        if(request('appointment_id')){
            $appointment =  $this->appointmentRepo->findById(request('appointment_id'));
        
            $labexams = $appointment->labexams()->where('patient_id',$id)->get();
            
        }else{
            $patient =  $this->patientRepo->findById($id);
            $labexams = $patient->labexams()->get();
        }

        $labexams = $labexams->groupBy(function($exam) {
            return $exam->date;
        })->toArray();

        $data = [];
        $exam = [];

        foreach ($labexams as $key => $value) {
        
            $exam['date'] = $key;
            $exam['exams'] = $value;
            $data[]= $exam;
        }


        return  $data;
 
     }

     public function marketing()
     {
        $emailsUsers = [];
        $tokensUsers = [];

        $mimes = ['jpg', 'jpeg', 'bmp', 'png'];
        $fileUploaded = 'error';

        if (request()->file('file')) {

            $file = request()->file('file');
            $name = $file->getClientOriginalName();
            $ext = $file->guessClientExtension();
            $onlyName = str_slug(pathinfo($name)['filename'], '-');

            if (in_array($ext, $mimes)) {
            
                $fileUploaded = $file->storeAs('clinics/' . auth()->id() . '/marketing','ad.'. $ext, 'public');

            
                $patients = Patient::with(['user' => function ($query) {

                    $query->whereHas('roles', function ($q) {
                        $q->where('name', 'paciente');
                    });

                }])->whereIn('id', request('patients'))->get();

                foreach ($patients as $patient) {
                    if ($email = $patient->user->first()->email) {
                        $emailsUsers[] = $email;
                    }
                    if ($token = $patient->user->first()->push_token) {
                        $tokensUsers[] = $token;
                    }
                }

                if (count($tokensUsers)) {
                   
                    $push = new PushNotification('fcm');
                /*$response = $push->setMessage([
                       'title'=>'Nueva Cita Reservada',
                       'body'=>'Para el '.  Carbon::parse($appointment->start)->toDateTimeString(),
                       'badge' => '1'
                   ])->setApiKey(env('API_WEB_KEY_FIREBASE_MEDICS'))
                    ->setDevicesToken($medic->push_token)
                    ->send()
                    ->getFeedback();
                    
                    Log::info('Mensaje Push code: '.$response->success);
                     */
                    $response = $push->setMessage([
                        'notification' => [
                            'title' => 'Nuevo Anuncio!!',
                            'body' => 'Te ha llegado una nueva notificacion de informacion de interes, revisala en el panel de notificaciones!!',
                            'sound' => 'default'
                        ],
                        'data' => [
                            'tipo' => 'marketing',
                            'media' => 'storage/'.$fileUploaded,

                        ]

                    ])->setApiKey(env('API_WEB_KEY_FIREBASE_MEDICS'))
                        ->setDevicesToken($tokensUsers)
                        ->send()
                        ->getFeedback();

                    Log::info('Mensaje Push code: ' . $response->success);


                }

                // if ($emailsUsers) {
                //     try {
                //         \Mail::to($emailsUsers)->send(new NewMarketing([]));
                //     } catch (\Swift_TransportException $e) {  //Swift_RfcComplianceException
                //         Log::error($e->getMessage());
                //     }
                // }

                flash('Anuncio enviado', 'success');
            }

            
            return back();
        }

    


       

     }
}
