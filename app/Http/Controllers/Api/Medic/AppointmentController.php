<?php

namespace App\Http\Controllers\Api\Medic;

use App\Appointment;
use App\Http\Controllers\Api\ApiController;
use App\Mail\NewAppointment;
use App\Reminder;
use App\Repositories\AppointmentRepository;
use App\Repositories\PatientRepository;
use App\User;
use Carbon\Carbon;
use Edujugon\PushNotification\PushNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

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

    public function index(Request $request){

        $user = $request->user();

        /*$appointments =  $this->appointmentRepo->findAllByDoctor($user->id);

        return $appointments;*/
        $date1 = Carbon::now();
        $date2 = Carbon::now()->addWeek();
       
        
     
        $appointments = Appointment::with('patient','user','office')->where('user_id',$user->id)->where([['appointments.date', '>=', $date1->startOfDay()],
                ['appointments.date', '<=', $date2->endOfDay()]])->orderBy('appointments.date', 'DESC')->orderBy('appointments.start', 'DESC')->get();
        




       $grouped = $appointments->groupBy(function($item) {
           
            return Carbon::parse($item->date)->toDateString();
        })->toArray();
        
      
        $dataAppointments = [];
        $item = [];

        foreach ($grouped as $key => $value) {
        
            $item['date'] = $key;
            $item['appointments'] = $value;
            $dataAppointments[]= $item;
        }

     
        return $dataAppointments;
     

    }
     /**
     * Mostrar vista de actualizar consulta(cita)
     */
    public function show($id)
    {

        $appointment =   $this->appointmentRepo->findById($id)->load('diseaseNotes','physicalExams','diagnostics','treatments'); 
        $patient = $appointment->patient;
        $medicines = ($appointment->medicines) ? $appointment->medicines : [];
        $vitalSigns = ($appointment->vitalSigns) ? $appointment->vitalSigns : [];
        $labexams = $appointment->labexams()->where('patient_id',$patient->id)->limit(10)->get();
        $labresults = $patient->labresults()->limit(10)->get();
        

        $labexams = $labexams->groupBy(function($exam) {
            return $exam->date;
        })->toArray();

        $dataExams = [];
        $exam = [];

        foreach ($labexams as $key => $value) {
        
            $exam['date'] = $key;
            $exam['exams'] = $value;
            $dataExams[]= $exam;
        }
        
        //$files = Storage::disk('public')->files("patients/". $patient->id ."/files");
        $files = $patient->archivos()->where('appointment_id',$id)->get();
        $data = [
           'appointment' => $appointment,
           'files' => $files,
           'medicines' => $medicines,
           'vitalSigns' => $vitalSigns,
           'labexams' => $dataExams,
           'labresults' => $labresults
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

                    Log::info('Mensaje Push code: '.$response->success);
           }
        if($appointment->patient && $appointment->patient->email){
            try {
                            
                \Mail::to([$appointment->patient->email,$appointment->user->email])->send(new NewAppointment($appointment));
                
            }catch (\Swift_TransportException $e)  //Swift_RfcComplianceException
            {
                Log::error($e->getMessage());
            }
        }else{
             try {
                \Mail::to($appointment->user->email)->send(new NewAppointment($appointment));
            } catch (\Swift_TransportException $e) {  //Swift_RfcComplianceException
                Log::error($e->getMessage());
            }

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
    public function delete($id, Request $request)
    {
        $user = $request->user();
        $appointment = $this->appointmentRepo->findById($id);
        $result = 0;

        if(!$appointment) return $result;

        if($user->hasRole('paciente')){

            if(!$appointment->isStarted() && $appointment->created_by == $user->id)//if( !$appointment->isStarted() && $appointment->isOwner($user) )
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

    


   
}
