<?php

namespace App\Http\Controllers;

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
     * Lista de todas las citas de un doctor sin paginar
     */
    public function store()
    {
        
        $appointment = $this->appointmentRepo->store(request()->all());
        $appointment['patient'] = $appointment->patient;
        $appointment['user'] = $appointment->user;
        

        return $appointment;

        
        
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
        
        if($appointment !== true)  return $appointment; //no se elimino correctamente

        return '';

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
