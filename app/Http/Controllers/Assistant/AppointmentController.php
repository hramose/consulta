<?php

namespace App\Http\Controllers\Assistant;

use App\Http\Controllers\Controller;
use App\Patient;
use App\Repositories\AppointmentRepository;
use App\Repositories\MedicRepository;
use App\Repositories\OfficeRepository;
use App\Repositories\findAllByDoctor;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AppointmentController extends Controller
{
    
    function __construct(AppointmentRepository $appointmentRepo, OfficeRepository $officeRepo, MedicRepository $medicRepo)
    {
    	
        $this->middleware('auth');
    	$this->appointmentRepo = $appointmentRepo;
        $this->officeRepo = $officeRepo;
        $this->medicRepo = $medicRepo;

    }

    /**
     * Mostrar vista de todas las consulta(citas) de un doctor
     */
    public function index()
    {
       
        //dd(auth()->user()->assistants->first());
        $boss = \DB::table('assistants_users')->where('assistant_id',auth()->id())->first();
      
        $office = User::find($boss->user_id)->offices->first();

        $medics = $this->medicRepo->findAllByOffice($office->id);

        if(request('medic'))
            $medic = $this->medicRepo->findById(request('medic'));
        else
            $medic = null;
      
        return view('assistant.agenda.index',compact('medics','medic','office'));


    }

  

    /**
     * Guardar consulta(cita)
     */
    public function store()
    {

        $appointment = $this->appointmentRepo->store(request()->all(),request('user_id'));
        
        if(!$appointment) return '';

        $appointment['patient'] = $appointment->patient;
        $appointment['user'] = $appointment->user;
        $appointment->load('office');

        //asignamos el paciente al medico despues de crear la cita desde el perfil de l clinica
        $user = User::find($appointment->user->id);

        if(!$user->hasPatient($appointment->patient->id))
            $patient = $user->patients()->attach($appointment->patient->id);

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
        $search['office'] = (auth()->user()->offices->count()) ? auth()->user()->offices->first()->id : '';

        $appointments = $this->appointmentRepo->findAllByDoctorWithoutPagination(request('medic'),$search);

        return $appointments;
        
    }

    

}
