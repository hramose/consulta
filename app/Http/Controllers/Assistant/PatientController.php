<?php

namespace App\Http\Controllers\Assistant;

use App\Http\Controllers\Controller;
use App\Http\Requests\PatientRequest;
use App\Mail\NewPatient;
use App\Repositories\AppointmentRepository;
use App\Repositories\PatientRepository;
use App\Repositories\UserRepository;
use App\User;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PatientController extends Controller
{
    function __construct(PatientRepository $patientRepo, AppointmentRepository $appointmentRepo, UserRepository $userRepo)
    {
    	$this->middleware('authByRole:asistente');
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

      
      
        $office = auth()->user()->clinicsAssistants->first();

       
        $patients = $this->patientRepo->findAllOfClinic($office,$search);
        


    	return view('assistant.patients.index',compact('patients','search','inita'));

    }

    /**
     * Mostrar vista crear paciente
     */
    public function create()
    {
        
        return view('assistant.patients.create');

    }

    /**
     * Guardar paciente
     */
    public function store(PatientRequest $request)
    {
        $this->validate(request(),[
            'email' => 'required|email|max:255|unique:users'
        ]);
        
        $boss_assistant = \DB::table('assistants_users')->where('assistant_id',auth()->id())->first();
      
        $boss = User::find($boss_assistant->user_id);
        
        $patient =$this->patientRepo->store($request->all(), $boss);
    

        $data = $request->all();
        $data['password'] = ($data['password']) ? $data['password'] : '123456';
        $data['name'] = $data['first_name'];
        $data['provider'] = 'email';
        $data['provider_id'] = $data['email'];
        $data['role'] = Role::whereName('paciente')->first();
        $data['api_token'] = str_random(50);

        
        $user = $this->userRepo->store($data);
        $user_patient = $user->patients()->save($patient);

        try {
                        
            \Mail::to($user)->send(new NewPatient($user));
            
        }catch (\Swift_TransportException $e)  //Swift_RfcComplianceException
        {
            \Log::error($e->getMessage());
        }

        flash('Paciente Creado','success');

        return Redirect('/assistant/patients/'.$patient->id.'/edit');

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
        
        return view('assistant.patients.edit', compact('patient','files','initAppointments','scheduledAppointments'));

    }

    /**
     * Actualizar Paciente
     */
    public function update($id)
    {
        $this->validate(request(),[
                'first_name' => 'required',
                'last_name' => 'required',
                'address' => 'required',  
                'province' => 'required',  
                'city' => 'required', 
                'phone' => 'required',
                'email' => ['required','email', Rule::unique('patients')->ignore($id) ]//'required|email|max:255|unique:patients',   
        ]);

        $patient = $this->patientRepo->update($id, request()->all());
        
        
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
        $boss_assistant = \DB::table('assistants_users')->where('assistant_id',auth()->id())->first();
      
        $boss = User::find($boss_assistant->user_id);

        $patients = $this->patientRepo->list(request('q'), $boss);
       
        return $patients;

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
