<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Http\Requests\PatientRequest;
use App\Repositories\AppointmentRepository;
use App\Repositories\PatientRepository;
use App\Repositories\UserRepository;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PatientController extends Controller
{
    function __construct(PatientRepository $patientRepo, AppointmentRepository $appointmentRepo, UserRepository $userRepo)
    {
    	$this->middleware('auth');
        $this->patientRepo = $patientRepo;
        $this->appointmentRepo = $appointmentRepo;
         $this->userRepo = $userRepo;
    }

    /**
     * Mostrar lista de pacientes de un doctor
     */
    public function index()
    {
        $search['q'] = request('q');
        $inita = null;
        
        if(request('p'))
            $p = Patient::find(request('p'));

        if(request('inita'))
            $inita = 1;

        $office = auth()->user()->offices->first();
       
        $patients = $this->patientRepo->findAllOfClinic($office,$search);
        


    	return view('clinic.patients.index',compact('patients','search','inita'));

    }

    /**
     * Mostrar vista crear paciente
     */
    public function create()
    {
        
        return view('clinic.patients.create');

    }

    /**
     * Guardar paciente
     */
    public function store(PatientRequest $request)
    {
        
        $patient =$this->patientRepo->store($request->all());

        $data = $request->all();
        $data['password'] = ($data['password']) ? $data['password'] : '123456';
        $data['name'] = $data['first_name'];
        $data['provider'] = 'email';
        $data['provider_id'] = $data['email'];
        $data['role'] = Role::whereName('paciente')->first();
        $data['api_token'] = str_random(50);

        $user_patient = $this->userRepo->store($data);
        $user_patient = $user_patient->patients()->save($patient);

        flash('Paciente Creado','success');

        return Redirect('/clinic/patients/'.$patient->id.'/edit');

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
        
        return view('clinic.patients.edit', compact('patient','files','initAppointments','scheduledAppointments'));

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
        /*if(auth()->user()->hasRole('asistente'))
        {
            $boss_assistant = \DB::table('assistants_users')->where('assistant_id',auth()->id())->first();
      
            $boss = User::find($boss_assistant->user_id);

            $patients = $this->patientRepo->list(request('q'), $boss);
            
            return $patients;
        }*/
       
        $patients = $this->patientRepo->listForClinics(request('q'));
       
        return $patients;

    }

     public function verifyIsPatient()
    {
         $patient = $this->patientRepo->findById(request('patient_id'));

        
        return ($patient->isPatientOf(request('medic_id'))) ? 'yes' :'no';

    }
    

    /**
     * Guardar paciente
     */
    public function addToYourPatients($id)
    {
      
        $id_patient_confirm = request('id_patient_confirm');
       
        if($id != $id_patient_confirm)
        {
         
         flash('Id de confirmacion incorrecto','danger');

         return back();
        }

        $patient = auth()->user()->patients()->attach($id);

        flash('Paciente Agregado a tu lista','success');

        return Redirect()->back();

    }

}
