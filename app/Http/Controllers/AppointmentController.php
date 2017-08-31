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
      
        

        return view('appointments.history',compact('initAppointments','scheduledAppointments'));

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
       
        return view('appointments.show',compact('appointment', 'files', 'history','appointments','patient'));

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
                $response = $push->setMessage([
                    'notification' => [
                            'title'=>'Notificación',
                            'body'=>'Un Usuario ha reservado una cita para el '.  Carbon::parse($appointment->start)->toDateTimeString(),
                            'sound' => 'default'
                            ]
                    
                    ])
                    ->setApiKey(env('API_WEB_KEY_FIREBASE_MEDICS'))
                    ->setDevicesToken($medic->push_token)
                    ->send()
                    ->getFeedback();
                    
                    Log::info('Mensaje Push code: '.$response->success);
           }
        
        try {
                        
            \Mail::to([$appointment->patient->email,$appointment->user->email])->send(new NewAppointment($appointment));
            
        }catch (\Swift_TransportException $e)  //Swift_RfcComplianceException
        {
            Log::error($e->getMessage());
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


   
}
