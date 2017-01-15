<?php

namespace App\Http\Controllers\Medic;

use App\Http\Controllers\Controller;
use App\Repositories\AppointmentRepository;
use App\Repositories\findAllByDoctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AppointmentController extends Controller
{
    
    function __construct(AppointmentRepository $appointmentRepo)
    {
    	
        $this->middleware('auth');
    	$this->appointmentRepo = $appointmentRepo;

    }

    /**
     * Mostrar vista de todas las consulta(citas) de un doctor
     */
    public function index()
    {
        $search['q'] = request('q');
      
    	$appointments =$this->appointmentRepo->findAllByDoctor(auth()->id(), $search);

    	return view('appointments.index',compact('appointments','search'));

    }

    /**
     * Mostrar vista de crear consulta(cita)
     */
    public function create()
    {

        $appointments = $this->appointmentRepo->findAllByDoctor(auth()->id());

    	return view('appointments.create',compact('appointments'));

    }

    /**
     * Guardar consulta(cita)
     */
    public function store()
    {

        $appointment = $this->appointmentRepo->store(request()->all());
        $appointment['patient'] = $appointment->patient;
        $appointment['user'] = $appointment->user;

        return $appointment;

    }

    /**
     * Mostrar vista de actualizar consulta(cita)
     */
    public function edit($id)
    {

        $appointment =  $this->appointmentRepo->update_status($id, 1);
        
        $files = Storage::disk('public')->files("patients/". $appointment->patient->id ."/files");
       
        return view('appointments.edit',compact('appointment', 'files'));

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
        }
        
        return $appointment;

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
    public function getAppointments()
    {

        $appointments = $this->appointmentRepo->findAllByDoctorWithoutPagination(auth()->id());

        return $appointments;
        
    }

    /**
     * imprime resumen de la consulta
     */
    public function printSummary($id)
    {

        $appointment =  $this->appointmentRepo->findById($id);

        
        return view('appointments.print-summary',compact('appointment'));
        
    }

}
