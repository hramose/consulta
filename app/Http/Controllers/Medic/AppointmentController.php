<?php

namespace App\Http\Controllers\Medic;

use App\Http\Controllers\Controller;
use App\Patient;
use App\Repositories\AppointmentRepository;
use App\Repositories\PatientRepository;
use App\Repositories\findAllByDoctor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AppointmentController extends Controller
{
    
    function __construct(AppointmentRepository $appointmentRepo, PatientRepository $patientRepo)
    {
    	
        $this->middleware('auth');
    	$this->appointmentRepo = $appointmentRepo;
        $this->patientRepo = $patientRepo;

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
    
         // por si le da desde el formulario de paciente crear la cita a este paciente sin tener que buscarlo
        $p = null;
        $wizard = null;
        
        if(request('p'))
            $p = Patient::find(request('p'));

        if(request('wizard'))
            $wizard = 1;

        $appointments = $this->appointmentRepo->findAllByDoctor(auth()->id());

       $month = Carbon::now()->month;
       //$carbon = new Carbon(new Carbon(date('Y-m-d', strtotime('next monday', strtotime('2017-' . $month . '-01'))), 'America/Costa_Rica'));
        $carbon = Carbon::now()->startOfMonth();
        $weeks_array = [];
        $selectWeeks = [];
        while (intval($carbon->month) == intval($month)){
            $weeks_array[$carbon->weekOfMonth][ $carbon->dayOfWeek ] = $carbon->toDateString();
            $carbon->addDay();
          
        }
        foreach ($weeks_array as $key => $week) {
            //dd($key);

             $itemSelect = [
                'name' => 'Semana '. $key.' ('.head($week) .' | '.last($week).')',
                'value' => $key
            ];
             $selectWeeks [] = $itemSelect;

        }
        
       // dd($selectWeeks);

       
    	return view('appointments.create',compact('appointments', 'p','wizard','selectWeeks'));

    }

    /**
     * Guardar consulta(cita)
     */
    public function store()
    {

        $appointment = $this->appointmentRepo->store(request()->all());
        
        if(!$appointment) return '';

        $appointment['patient'] = $appointment->patient;
        $appointment['user'] = $appointment->user;
        $appointment->load('office');

        return $appointment;

    }

    /**
     * Mostrar vista de actualizar consulta(cita)
     */
    public function edit($id)
    {
       
        $appointment =  $this->appointmentRepo->update_status($id, 1);

        $history =  $this->patientRepo->findById($appointment->patient->id)->history;
        $appointments =  $this->patientRepo->findById($appointment->patient->id)->appointments->load('user','diagnostics');
        
        $files = Storage::disk('public')->files("patients/". $appointment->patient->id ."/files");
        
        $tab = request('tab');

        return view('appointments.edit',compact('appointment', 'files', 'history','appointments','tab'));

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

     public function noShows($id)
    {

        $reservation = \DB::table('appointments')
            ->where('id', $id)
            ->update(['status' => 2]); //no asistio a la cita  

         return back();

    }

     public function finished($id)
    {

        $reservation = \DB::table('appointments')
            ->where('id', $id)
            ->update(['finished' => 1]); //cita finalizada

         return 'finished';

    }


    /**
     * Lista de todas las citas de un doctor sin paginar
     */
    public function getAppointments()
    {
        $search = request()->all();
        $search['date1'] = isset($search['date1']) ? Carbon::parse($search['date1']) : '';
        $search['date2'] =  isset($search['date2']) ? Carbon::parse($search['date2']) : '';

        $appointments = $this->appointmentRepo->findAllByDoctorWithoutPagination(auth()->id(), $search);

        return $appointments;
        
    }

    /**
     * imprime resumen de la consulta
     */
    public function printSummary($id)
    {

        $appointment =  $this->appointmentRepo->findById($id);
        $history =  $this->patientRepo->findById($appointment->patient->id)->history;
        
        return view('appointments.print-summary',compact('appointment','history'));
        
    }

    /**
     * imprime resumen de la consulta
     */
    public function printTreatment($id)
    {

        $appointment =  $this->appointmentRepo->findById($id);

        
        return view('appointments.print-treatment',compact('appointment'));
        
    }

}
