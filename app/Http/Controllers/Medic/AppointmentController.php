<?php

namespace App\Http\Controllers\Medic;

use App\Http\Controllers\Controller;
use App\Patient;
use App\Treatment;
use App\Diagnostic;
use App\Repositories\AppointmentRepository;
use App\Repositories\PatientRepository;
use App\Repositories\findAllByDoctor;
//use Vsmoraes\Pdf\Pdf;
use PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Events\AppointmentDeleted;
use App\Events\AppointmentDeletedToAssistant;

class AppointmentController extends Controller
{
    
    function __construct(AppointmentRepository $appointmentRepo, PatientRepository $patientRepo)
    {
    	
        $this->middleware('auth');
    	$this->appointmentRepo = $appointmentRepo;
        $this->patientRepo = $patientRepo;
        //$this->pdf = $pdf;

    }

    /**
     * Mostrar vista de todas las consulta(citas) de un doctor
     */
    public function index()
    {
        $search['q'] = request('q');
      
    	$appointments =$this->appointmentRepo->findAllByDoctor(auth()->id(), $search);

    	return view('medic.appointments.index',compact('appointments','search'));

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

       
    	return view('medic.appointments.create',compact('appointments', 'p','wizard','selectWeeks'));

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
        $appointments =  $this->patientRepo->findById($appointment->patient->id)->appointments()->with('user','patient.medicines','diagnostics', 'diseaseNotes', 'physicalExams','treatments','labexams')->where('appointments.id','!=',$appointment->id)->where('status', 1)->where('appointments.date','<=',$appointment->date)->orderBy('start','DESC')->limit(3)->get();
        
        $files = $this->patientRepo->findById($appointment->patient->id)->archivos()->where('appointment_id',$id)->get(); //Storage::disk('public')->files("patients/". $appointment->patient->id ."/files");
       
        $tab = request('tab');

       
       
      

        return view('medic.appointments.edit',compact('appointment', 'files', 'history','appointments','tab'));

    }

     /**
     * Guardar consulta(cita)
     */
     public function createFrom($id)
     {
 
         $appointment = $this->appointmentRepo->findById($id);
         
        
         $data = [

            'patient_id' => $appointment->patient_id,
            'office_id' => $appointment->office_id,
            'date' => Carbon::now()->toDateString(),
            'start' =>Carbon::now()->toDateString().'T'.Carbon::now()->toTimeString(),
            'end' =>Carbon::now()->addMinutes(30)->toDateString().'T'.Carbon::now()->addMinutes(30)->toTimeString(),
            'allDay' => $appointment->allDay,
            'title' => 'Seguimiento de la cita '.$appointment->id,
            'backgroundColor' => $appointment->backgroundColor,
            'borderColor' => $appointment->borderColor,
            'medical_instructions' => $appointment->medical_instructions,
            'visible_at_calendar' => 0,
            'tracing' => $appointment->id
            
            
        ];

        $newAppointment = $this->appointmentRepo->store($data);

        // $newAppointment->diseaseNotes->fill($appointment->diseaseNotes->toArray());
        // $newAppointment->diseaseNotes->save();
        
        // $newAppointment->physicalExams->fill($appointment->physicalExams->toArray());
        // $newAppointment->physicalExams->save();

        // foreach($appointment->labexams as $exam){
        //     $newAppointment->labexams()->attach($exam);
        // }
        // foreach($appointment->diagnostics as $diag){
        //     Diagnostic::create([
        //         'appointment_id' =>  $newAppointment->id,
        //         'name' =>  $diag->name,
        //         'comments' =>  $diag->comments
        //     ]);
            
        // }
        // foreach($appointment->treatments as $treat){
        //     Treatment::create([
        //         'appointment_id' =>  $newAppointment->id,
        //         'name' =>  $treat->name,
        //         'comments' =>  $treat->comments
        //     ]);
        // }



        return Redirect('/medic/appointments/'.$newAppointment->id.'/edit');
 
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

    public function viewed($id)
    {
       
            $reservation = \DB::table('appointments')
            ->where('id', $id)
            ->update(['viewed' => 1]); //vista desde el panel de notificacion  

       

       

         return 'viewed';

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
     public function pdfSummary($id)
     {
       
         $appointment =  $this->appointmentRepo->findById($id);
         $history =  $this->patientRepo->findById($appointment->patient->id)->history;

         
        return view('medic.appointments.pdf-summary',compact('appointment','history'));
         
     }
     /**
     * imprime resumen de la consulta
     */
     public function pdf($id)
     {
            $appointment =  $this->appointmentRepo->findById($id);
        

            $html = request('htmltopdf');
            $pdf = new PDF($orientation = 'L', $unit = 'in', $format = 'A4', $unicode = false, $encoding = 'UTF-8', $diskcache = false, $pdfa = false);

            $pdf::SetFont('helvetica', '', 9);
           
            $pdf::SetTitle('Expediente Clínico');
            $pdf::AddPage('L', 'A4');
            $pdf::writeHTML($html, true, false, true, false, '');
           
            $pdf::Output('expediente-'.str_slug($appointment->patient->first_name).'-'. str_slug($appointment->patient->id).'.pdf'); 
       
         
       
         
     }
 

    /**
     * imprime resumen de la consulta
     */
    public function printSummary($id)
    {

        $appointment =  $this->appointmentRepo->findById($id);
        $history =  $this->patientRepo->findById($appointment->patient->id)->history;
        
        return view('medic.appointments.print-summary',compact('appointment','history'));
        
    }

    /**
     * imprime resumen de la consulta
     */
    public function printTreatment($id)
    {

        $appointment =  $this->appointmentRepo->findById($id);

        
        return view('medic.appointments.print-treatment',compact('appointment'));
        
    }

}
