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
use App\Events\AppointmentCreated;
use App\Events\AppointmentDeleted;
use App\Events\AppointmentCreatedToAssistant;
use App\Events\AppointmentDeletedToAssistant;

class AppointmentController extends Controller
{
    
    function __construct(AppointmentRepository $appointmentRepo, OfficeRepository $officeRepo, MedicRepository $medicRepo)
    {
    	
        $this->middleware('authByRole:asistente');
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
        /*$boss = \DB::table('assistants_users')->where('assistant_id',auth()->id())->first();
      
       
        if(auth()->user()->isMedicAssistant($boss->user_id)){
            $offices = User::find($boss->user_id)->offices()->where('type','Consultorio Independiente')->pluck('offices.id');//first();
            $office = User::find($boss->user_id)->offices->first();
        }

        if(auth()->user()->isClinicAssistant($boss->user_id)){
            $offices = User::find($boss->user_id)->offices()->where('type','Clínica Privada')->pluck('offices.id');
            $office = User::find($boss->user_id)->offices->first();
        }*/

        $office = auth()->user()->clinicsAssistants->first();

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

         event(new AppointmentCreated($appointment));
         event(new AppointmentCreatedToAssistant($appointment));
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
        $appointmentDeletedToPusher = $this->appointmentRepo->findById($id);
        
        $appointment = $this->appointmentRepo->delete($id);

        ($appointment === true) ? flash('Consulta eliminada correctamente!','success') : flash('No se puede eliminar consulta ya que se encuentra iniciada','error');

        event(new AppointmentDeleted($appointmentDeletedToPusher));
        event(new AppointmentDeletedToAssistant($appointmentDeletedToPusher));

        return back();

    }
     /**
     * Eliminar consulta(cita) ajax desde calendar
     */
    public function delete($id)
    {
        $appointmentDeletedToPusher = $this->appointmentRepo->findById($id);
        $appointment = $this->appointmentRepo->delete($id);
        
        if($appointment !== true)  return $appointment; //no se elimino correctamente

        event(new AppointmentDeleted($appointmentDeletedToPusher));
        event(new AppointmentDeletedToAssistant($appointmentDeletedToPusher));

        return '';

    }

    /**
     * Lista de todas las citas de un doctor sin paginar
     */
    public function getAppointments()
    {
        $search['office'] = (auth()->user()->offices->count()) ? auth()->user()->offices->first()->id : '';
        
        $search['date1'] = isset($search['date1']) ? Carbon::parse($search['date1']) : '';
        $search['date2'] =  isset($search['date2']) ? Carbon::parse($search['date2']) : '';

        $appointments = $this->appointmentRepo->findAllByDoctorWithoutPagination(request('medic'),$search);

        return $appointments;
        
    }

    public function viewed($id)
    {
        
            $reservation = \DB::table('appointments')
            ->where('id', $id)
            ->update(['viewed_assistant' => 1]); //vista desde el panel de notificacion  
       

       

         return 'viewed';

    }
    

    

}
