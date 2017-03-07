<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\Mail\NewAppointment;
use App\Repositories\AppointmentRepository;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class AppointmentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AppointmentRepository $appointmentRepo)
    {
        $this->middleware('auth');
        $this->appointmentRepo = $appointmentRepo;
  

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

        if($appointment->user) //agregar paciente del usuario al medico tambien
            {
                $medic = User::find($appointment->user->id);

                $ids_patients = $medic->patients()->pluck('patient_id')->all();

                $ids_patients[] = $appointment->patient->id;

                $medic->patients()->sync($ids_patients);
            }
        
        \Mail::to([$appointment->patient->email,$appointment->user->email])->send(new NewAppointment($appointment));

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

        $appointments = $this->appointmentRepo->findAllByDoctorWithoutPagination($id);
        
        return $appointments;
        
    }

   
}
