<?php

namespace App\Http\Controllers;

use Edujugon\PushNotification\PushNotification;
use App\Appointment;
use App\Mail\NewAppointment;
use App\Reminder;
use App\Repositories\AppointmentRepository;
use App\Repositories\PatientRepository;
use Carbon\Carbon;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;
use App\Events\AppointmentCreated;
use App\Events\AppointmentDeleted;
use App\Events\AppointmentCreatedToAssistant;
use App\Events\AppointmentDeletedToAssistant;
class AppointmentController extends Controller
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
     * Mostrar vista de todas las consulta(citas) de un doctor
     */
    public function index()
    {
        
        $appointments = Appointment::where('created_by',auth()->id())->get();
        

        $initAppointments = $appointments->filter(function ($item, $key) {
                return $item->status > 0;
            });

        $scheduledAppointments = $appointments->filter(function ($item, $key) {
                return $item->status == 0;
            });
      
        

        return view('medic.appointments.history',compact('initAppointments','scheduledAppointments'));

    }

    /**
     * Mostrar vista de actualizar consulta(cita)
     */
    public function show($id)
    {

        $appointment =   $this->appointmentRepo->findById($id); //$this->appointmentRepo->update_status($id, 1);
        $patient = $appointment->patient;

        if(!auth()->user()->hasPatient($patient->id)) // verifica que el paciente es del usuario logueado
            return redirect('/');
        
        $history =  $this->patientRepo->findById($patient->id)->history;
        $appointments =  $this->patientRepo->findById($patient->id)->appointments->load('user','diagnostics');
        
        $files = Storage::disk('public')->files("patients/". $patient->id ."/files");
       
        return view('medic.appointments.show',compact('appointment', 'files', 'history','appointments','patient'));

    }

    /**
     * Lista de todas las citas de un doctor sin paginar
     */
    public function store()
    {
         $appointmentsToday = auth()->user()->appointmentsToday();
         
        if($appointmentsToday >= 2)
            return 'max-per-day';

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
                            'title'=>'Nueva Cita Reservada',
                            'body'=>'Para el '.  Carbon::parse($appointment->start)->toDateTimeString(),
                            'sound' => 'default'
                            ],
                    'data' => [
                       'tipo' => 'appointment',
                       
                       ]
                    
                    ])->setApiKey(env('API_WEB_KEY_FIREBASE_MEDICS'))
                    ->setDevicesToken($medic->push_token)
                    ->send()
                    ->getFeedback();
                    
                    Log::info('Mensaje Push code: '.$response->success);
           }
        
       // $appointmentToPusher = Appointment::with('patient','user')->find($appointment->id);

        event(new AppointmentCreated($appointment));
        event(new AppointmentCreatedToAssistant($appointment));
        
        if ($appointment->patient->email) {
            try {
                \Mail::to([$appointment->patient->email, $appointment->user->email])->send(new NewAppointment($appointment));
            } catch (\Swift_TransportException $e) {  //Swift_RfcComplianceException
                Log::error($e->getMessage());
            }
        }else{
            try {
                \Mail::to($appointment->user->email)->send(new NewAppointment($appointment));
            } catch (\Swift_TransportException $e) {  //Swift_RfcComplianceException
                Log::error($e->getMessage());
            }

        }

        $appointmentsToday = auth()->user()->appointmentsToday();

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
        $appointmentDeletedToPusher = $this->appointmentRepo->findById($id);
        
        $appointment = $this->appointmentRepo->delete($id);
        $appointmentsToday = auth()->user()->appointmentsToday();
        
        if($appointment !== true)
        {
             $data = [
                    'resp' => 'error',
                    'appointmentsToday' => $appointmentsToday
                ];

            return $data;//$appointment; //no se elimino correctamente
        }  

        $data = [
            'resp' => 'ok',
            'appointmentsToday' => $appointmentsToday
        ];

        event(new AppointmentDeleted($appointmentDeletedToPusher));
        event(new AppointmentDeletedToAssistant($appointmentDeletedToPusher));

        return $data;

    }
    /**
     * Eliminar consulta(cita)
     */
    public function destroy($id)
    {

        $appointment = $this->appointmentRepo->delete($id);

        ($appointment === true) ? flash('Consulta eliminada correctamente!','success') : flash('No se puede eliminar consulta ya que se encuentra iniciada','error');

        return back();

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

    /**
     * imprime resumen de la consulta
     */
     public function printSummary($id)
     {
 
         $appointment =  $this->appointmentRepo->findById($id);
         $history =  $this->patientRepo->findById($appointment->patient->id)->history;
         
         return view('medic.appointments.patient-print-summary',compact('appointment','history'));
         
     }
 
     /**
      * imprime resumen de la consulta
      */
     public function printTreatment($id)
     {
 
         $appointment =  $this->appointmentRepo->findById($id);
 
         
         return view('medic.appointments.patient-print-treatment',compact('appointment'));
         
     }


   
}
