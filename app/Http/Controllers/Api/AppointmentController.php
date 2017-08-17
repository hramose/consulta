<?php

namespace App\Http\Controllers\Api;

use App\Appointment;
use App\Mail\NewAppointment;
use App\Reminder;
use App\Repositories\AppointmentRepository;
use App\Repositories\PatientRepository;
use App\User;
use Illuminate\Http\Request;
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

    

    /**
     * Lista de todas las citas de un doctor sin paginar
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $appointmentsToday = $user->appointmentsToday();
         
        if($appointmentsToday >= 2)
            return 'max-per-day';

        $appointment = $this->appointmentRepo->store(request()->all(), request('user_id'));
        $appointment['patient'] = $appointment->patient;
        $appointment['user'] = $appointment->user;
        $appointment->load('office');

        if($appointment->user) //agregar paciente del usuario al medico tambien
            {
                $medic = User::find($appointment->user->id);

                $ids_patients = $medic->patients()->pluck('patient_id')->all();

                $ids_patients[] = $appointment->patient->id;

                $medic->patients()->sync($ids_patients);
            }
        
        try {
                        
            \Mail::to([$appointment->patient->email,$appointment->user->email])->send(new NewAppointment($appointment));
            
        }catch (\Swift_TransportException $e)  //Swift_RfcComplianceException
        {
            \Log::error($e->getMessage());
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
