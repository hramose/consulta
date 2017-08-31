<?php

namespace App\Http\Controllers\Api;

use Edujugon\PushNotification\PushNotification;
use App\Appointment;
use App\Mail\NewAppointment;
use App\Reminder;
use App\Repositories\AppointmentRepository;
use App\Repositories\PatientRepository;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;

class AppointmentController extends ApiController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AppointmentRepository $appointmentRepo, PatientRepository $patientRepo)
    {
        $this->middleware('auth')->except('sendReminder');
        $this->appointmentRepo = $appointmentRepo;
        $this->patientRepo = $patientRepo;
  

    }

    
     /**
     * Mostrar vista de actualizar consulta(cita)
     */
    public function show($id)
    {

        $appointment =   $this->appointmentRepo->findById($id)->load('diseaseNotes','physicalExams','diagnostics','treatments'); 
        $patient = $appointment->patient;
        $medicines = $patient->medicines;
        $vitalSigns = $patient->vitalSigns;
        
        $files = Storage::disk('public')->files("patients/". $patient->id ."/files");
        $data = [
           'appointment' => $appointment,
           'files' => $files,
           'medicines' => $medicines,
           'vitalSigns' => $vitalSigns
        ];

        return $data;

    }

    /**
     * Lista de todas las citas de un doctor sin paginar
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $appointmentsToday = $user->appointmentsToday();
         
        if($appointmentsToday >= 2)
            return $this->respondWithError('No se puede crear más de dos citas al dia!!');

        $appointment = $this->appointmentRepo->store(request()->all(), request('user_id'));
        $appointment['patient'] = $appointment->patient;
        $appointment['user'] = $appointment->user;
        $appointment->load('office');
        
        $medic = User::find(request('user_id'));
        
        if($appointment->user) //agregar paciente del usuario al medico tambien
            {
                //$medic = User::find($appointment->user->id);

                $ids_patients = $medic->patients()->pluck('patient_id')->all();

                $ids_patients[] = $appointment->patient->id;

                $medic->patients()->sync($ids_patients);
            }
           

           if($medic->push_token){
                $push = new PushNotification('fcm');
                $response = $push->setMessage([
                    'notification' => [
                            'title'=>'Notificación',
                            'body'=>'Un Usuario ha reservado una cita para el '.Carbon::parse($appointment->start)->toDateTimeString(),
                            'sound' => 'default'
                            ]
                    
                    ])
                    ->setApiKey(env('API_WEB_KEY_FIREBASE_MEDICS'))
                    ->setDevicesToken($medic->push_token)
                    ->send()
                    ->getFeedback();

                    Log::info($response);
           }
        
        try {
                        
            \Mail::to([$appointment->patient->email,$appointment->user->email])->send(new NewAppointment($appointment));
            
        }catch (\Swift_TransportException $e)  //Swift_RfcComplianceException
        {
            Log::error($e->getMessage());
        }
        

        $appointmentsToday = $user->appointmentsToday();

        $resp = [
            'appointment' => $appointment,
            'appointmentsToday' => $appointmentsToday
        ];

        return $resp;

        
        
    }

     /**
     * Actualizar consulta(cita)
     */
    public function update($id)
    {
        
        $appointment = $this->appointmentRepo->update($id, request()->all());
     
        if($appointment){

            $appointment['patient'] = $appointment->patient;
            $appointment['user'] = $appointment->user;

            if(request('medic_id')) //agregar paciente del usuario al medico tambien
            {
                $medic = User::find(request('medic_id'));

                $ids_patients = $medic->patients()->pluck('patient_id')->all();

                $ids_patients[] = $appointment->patient->id;

                $medic->patients()->sync($ids_patients);
            }
            
        }
        

        return $appointment;

    }

     /**
     * Eliminar consulta(cita) ajax desde calendar
     */
    public function delete($id)
    {

        $appointment = $this->appointmentRepo->findById($id);
        $result = 0;

        if(!$appointment) return $result;

        if(request()->user()->hasRole('paciente')){

            if( !$appointment->isStarted() && $appointment->isOwner(request()->user()) )
            {
                $appointment->reminders()->delete();

               $appointment = $appointment->delete();

                $result = 1;
            }else{
                $result = 2;
            }
        }else{

            if( !$appointment->isStarted() )
            {
                $appointment->reminders()->delete();
                
                $appointment = $appointment->delete();

                 $result = 1;
            }else{
                $result = 2;
            }
        }


        
         if($result == 0)
         {
             return $this->respondNotFound('Error al eliminar la cita');
 
         }
         if($result == 2)
         {
             return $this->respondForbidden('No se puede eliminar la cita por que ya esta iniciada');
 
         }

         
         return $this->respondDeleted('Cita Eliminada Correctamente');
        

    }
   


    /**
     * Lista de todas las citas de un doctor sin paginar
     */
    public function getAppointments($id)
    {
        $search = request()->all();
        $search['date1'] = isset($search['date1']) ? Carbon::parse($search['date1']) : '';
        $search['date2'] =  isset($search['date2']) ? Carbon::parse($search['date2']) : '';

        $appointments = $this->appointmentRepo->findAllByDoctorWithoutPagination($id, $search);
        
        return $appointments;
        
    }

    public function reminder($id)
    {
    
        $data = request()->all();

        $data['appointment_id'] = $id;
        
        $reminder = Reminder::create($data);

        return $reminder;

    }

    public function sendReminder()
    {
    
        $exitCode = \Artisan::call('consulta:reminderAppointment');

        return $exitCode;

    }


   
}
