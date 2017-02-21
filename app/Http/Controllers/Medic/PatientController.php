<?php

namespace App\Http\Controllers\Medic;

use App\Http\Controllers\Controller;
use App\Http\Requests\PatientRequest;
use App\Repositories\AppointmentRepository;
use App\Repositories\PatientRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PatientController extends Controller
{
    function __construct(PatientRepository $patientRepo, AppointmentRepository $appointmentRepo)
    {
    	$this->middleware('auth');
        $this->patientRepo = $patientRepo;
        $this->appointmentRepo = $appointmentRepo;
    }

    /**
     * Mostrar lista de pacientes de un doctor
     */
    public function index()
    {

    	$patients = $this->patientRepo->findAll();

    	return view('patients.index',compact('patients'));

    }

    /**
     * Mostrar vista crear paciente
     */
    public function create()
    {
        
        return view('patients.create');

    }

    /**
     * Guardar paciente
     */
    public function store(PatientRequest $request)
    {
        
        $patient =$this->patientRepo->store($request->all());

        flash('Paciente Creado','success');

        return Redirect('/medic/patients/'.$patient->id.'/edit');

    }
    /**
     * Mostrar vista editar paciente
     */
    public function edit($id)
    {
        
        $patient = $this->patientRepo->findById($id);

        $appointments = $this->appointmentRepo->findAllByPatient($id);

        $initAppointments = $appointments->filter(function ($item, $key) {
                return $item->status > 0;
            });

        $scheduledAppointments = $appointments->filter(function ($item, $key) {
                return $item->status == 0;
            });

        
        $files = Storage::disk('public')->files("patients/". $id ."/files");
        
        return view('patients.edit', compact('patient','files','initAppointments','scheduledAppointments'));

    }

    /**
     * Actualizar Paciente
     */
    public function update($id, PatientRequest $request)
    {
       
        $patient = $this->patientRepo->update($id, $request->all());
        
        flash('Paciente Actualizado','success');

        return back();

    }

     /**
     * Eliminar consulta(cita)
     */
    public function destroy($id)
    {

        $patient = $this->patientRepo->delete($id);

        ($patient === true) ? flash('Paciente eliminado correctamente!','success') : flash('No se puede eliminar paciente por que tiene citas asignadas','error');

        return back();

    }

    /**
     * Guardar foto de paciente
     */
    public function photos()
    {
       
        $mimes = ['jpg','jpeg','bmp','png'];
        $fileUploaded = "error";
       
        if(request('patient_id') && request()->file('photo'))
        {
        
            $file = request()->file('photo');

            $ext = $file->guessClientExtension();

            if(in_array($ext, $mimes))
                $fileUploaded = $file->storeAs("patients/". request('patient_id'), "photo.jpg",'public');
        }

        return $fileUploaded;

    }

    /**
     * Guardar archivos de paciente
     */
    public function files()
    {
    
        $mimes = ['jpg','jpeg','bmp','png','pdf','doc','docx'];
        $fileUploaded = "error";

        if(request('patient_id') && request()->file('file'))
        {     
            $file = request()->file('file');
            
            $name = $file->getClientOriginalName();
           
            $ext = $file->guessClientExtension();

            if(in_array($ext, $mimes))
                $fileUploaded = $file->storeAs("patients/". request('patient_id')."/files", $name,'public');        
        } 
       
        return $fileUploaded;

    }

    /**
     * Eliminar archivos de pacientes
     */
    public function deleteFiles()
    {
        
        Storage::disk('public')->delete(request('file'));
        
        return '';

    }

    /**
     * Guardar historial medico de paciente
     */
    public function history($id)
    {    
       
       $history = $this->patientRepo->updateHistory($id, request('data'));
        
       return 'Historial Medico guardado';

    }

    /**
     * Agregar medicamentos a pacientes
     */
    public function medicines($id)
    {
    
       $medicine = $this->patientRepo->addMedicine($id, request()->all());
        
       return  $medicine;

    }

    /**
     * Eliminar medicamentos a pacientes
     */
    public function deleteMedicines($id)
    {
      
        $medicine = $this->patientRepo->deleteMedicine($id);
        
        return '';

    }

    /**
     * Mostrar lista de pacientes para el formulario de consultas(citas)
     */
    public function list()
    {
          
        $patients = $this->patientRepo->list(request('q'));
       
        return $patients;

    }

}
