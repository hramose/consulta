<?php

namespace App\Http\Controllers\Api\Medic;

use App\Allergy;
use App\Appointment;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\PatientRequest;
use App\Labexam;
use App\Labresult;
use App\Mail\NewPatient;
use App\Pressure;
use App\Repositories\AppointmentRepository;
use App\Repositories\PatientRepository;
use App\Repositories\UserRepository;
use App\Role;
use App\Sugar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;

class PatientController extends ApiController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PatientRepository $patientRepo, UserRepository $userRepo)
    {
       
        $this->patientRepo = $patientRepo;
        $this->userRepo = $userRepo;
        
    }



    /**
     * Guardar paciente
     */
    public function store(PatientRequest $request)
    {
      
       // $patient =$this->patientRepo->store($request->all(),$request->user());
       //validamos que en users no hay email que va a registrase como paciente
       


        $patient =$this->patientRepo->store($request->all(), $request->user());

        $data = $request->all();

        if (isset($data['email']) && $data['email']) {

            $this->validate(request(), [
                'phone' => 'required|unique:users',
                'email' => 'required|email|max:255|unique:users'
            ]);


            $data['password'] = (isset($data['password']) && $data['password']) ? $data['password'] : $data['phone'];


            $data['name'] = $data['first_name'];
            $data['provider'] = 'email';
            $data['provider_id'] = $data['email'];
            $data['role'] = Role::whereName('paciente')->first();
            $data['api_token'] = str_random(50);

            $user = $this->userRepo->store($data);
            $user_patient = $user->patients()->save($patient);

            try {
                \Mail::to($user)->send(new NewPatient($user));
            } catch (\Swift_TransportException $e) {  //Swift_RfcComplianceException
                \Log::error($e->getMessage());
            }
        }else{
             $this->validate(request(), [
                'phone' => 'required|unique:users',
            ]);

          $data['password'] = (isset($data['password']) && $data['password']) ? $data['password'] : $data['phone'];


            $data['name'] = $data['first_name'];
            $data['provider'] = 'phone';
            $data['provider_id'] = $data['phone'];
            $data['role'] = Role::whereName('paciente')->first();
            $data['api_token'] = str_random(50);

            $user = $this->userRepo->store($data);
            $user_patient = $user->patients()->save($patient);


        }


        return $patient;

    }

    /**
     * Actualizar Paciente
     */
    public function update($id)
    {

        $this->validate(request(),[
                'first_name' => 'required',
                'last_name' => 'required',
                //'address' => 'required',  
                'province' => 'required',  
                //'city' => 'required', 
                'phone' => ['required', Rule::unique('patients')->ignore($id) ],
                'email' => ['email', Rule::unique('patients')->ignore($id) ]//'required|email|max:255|unique:patients',   
        ]);

        $patient = $this->patientRepo->findById($id);
        
        $user_patient = $patient->user()->whereHas('roles', function ($query){
                        $query->where('name',  'paciente');
                    })->first();

        if($user_patient){

            $this->validate(request(),[ //se valida que no exista en user el correo q quiere cambiar
                    'phone' => ['required', Rule::unique('users')->ignore($user_patient->id)],
                    'email' => ['email', Rule::unique('users')->ignore($user_patient->id)]
            ]);
        }else{
            $this->validate(request(),[ //se valida que no exista en user el correo q quiere cambiar
                    'phone' => ['required', Rule::unique('users')],
                    'email' => ['email', Rule::unique('users')]
            ]);
        }

        $patient = $this->patientRepo->update($id, request()->all());
        

        return $patient;

    }
     /**
     * Eliminar consulta(cita)
     */
    public function destroy($id)
    {

        $patient = $this->patientRepo->delete($id);

        if($patient === true) return $this->respondDeleted('Paciente Eliminado Correctamente');
     
 
        return $this->respondforbidden('No se puede eliminar paciente por que tiene citas asignadas','error');
        

    }

     /**
     * Agregar medicamentos a pacientes
     */
    public function medicines($id)
    {
    
       $medicine = $this->patientRepo->addMedicine($id, request()->all());
        
       return $medicine;

    }
     /**
     * Agregar medicamentos a pacientes
     */
    public function getMedicines($id)
    {
       $patient = $this->patientRepo->findById($id);
       $medicines = $patient->medicines;
        
       return $medicines;

    }

    /**
     * Eliminar medicamentos a pacientes
     */
    public function deleteMedicines($id)
    {
      
        $medicine = $this->patientRepo->deleteMedicine($id);
        
       

       if(! $medicine)
         {
             return $this->respondNotFound('Medicina does not exist');
 
         }

         
         return $this->respondDeleted('Medicina Eliminada Correctamente');

    }

   

     /**
     * Agregar medicamentos a pacientes
     */
    public function getAllergies($id)
    {
       $patient = $this->patientRepo->findById($id);
       $allergies = $patient->history->allergies->load('user.roles');
        
       return $allergies;

    }

  
     /**
     * Agregar medicamentos a pacientes
     */
    public function getHistory($id)
    {
       $patient = $this->patientRepo->findById($id);
       $history = $patient->history;
       $appointments = $patient->appointments()->with('user','diagnostics','diseaseNotes','labexams','treatments','physicalExams')->where('status', 1)->orderBy('start','DESC')->limit(3)->get();//$patient->appointments->load('user','diagnostics');
       $labresults = $patient->labresults()->limit(10)->get();
       // $labexams =  $patient->labexams()->with('results')->limit(10)->get();

       //  $labexams = $labexams->groupBy(function($exam) {
       //      return $exam->date;
       //  })->toArray();

       //  $dataExams = [];
       //  $exam = [];

       //  foreach ($labexams as $key => $value) {
        
       //      $exam['date'] = $key;
       //      $exam['exams'] = $value;
       //      $dataExams[]= $exam;
       //  }


        $data = [
            'history' => $history,
            'appointments'=> $appointments,
            'labresults'=> $labresults
            
        ];

       return $data;

    }

    /**
     * Agregar medicamentos a pacientes
     */
     public function getLabExams($id)
     {
        if(request('appointment_id')){
            $appointment =  $this->appointmentRepo->findById(request('appointment_id'));
        
            $labexams = $appointment->labexams()->with('results')->where('patient_id',$id)->get();
            
        }else{
            $patient =  $this->patientRepo->findById($id);
            $labexams = $patient->labexams()->with('results')->get();
        }

        $labexams = $labexams->groupBy(function($exam) {
            return $exam->date;
        })->toArray();

        $data = [];
        $exam = [];

        foreach ($labexams as $key => $value) {
        
            $exam['date'] = $key;
            $exam['exams'] = $value;
            $data[]= $exam;
        }


        return  $data;
 
     }
     /**
     * Agregar medicamentos a pacientes
     */
     public function labExams($id)
     {
        $this->validate(request(),[
                'date' => 'required',
                'name' => 'required',
               
                
        ]);
        $data['date'] =request('date');
        $data['name'] =request('name');
        $data['patient_id'] = $id;

       
        $labexam = Labexam::create($data);
        $labexam->load('results');


        $labexam->appointments()->attach(request('appointment_id')); // asociar la cita con el paciente

        return $labexam;
 
     }

      /**
      * Eliminar medicamentos a pacientes
      */
      public function deleteLabExams($id)
      {
        
          $exam = Labexam::find($id);
          $appointment = Appointment::find(request('appointment_id'));
          //dd($exam->appointments()->where('appointments.id','<>', request('appointment_id'))->count());
          if(!$exam->appointments()->where('appointments.id','<>', request('appointment_id'))->count())
          {
              
              $exam->delete();
  
              
  
              return '';

          }
        
          $patient = $appointment->labexams()->detach($id); 
          

          
          return '';
  
      }

     /**
     * Agregar medicamentos a pacientes
     */
     public function labResults($id)
     {
        $this->validate(request(),[
                'date' => 'required',
                'file' => 'required',
                
        ]);
        $data = request()->all();
        $data['patient_id'] = $id;

        $mimes = ['jpg','jpeg','bmp','png','pdf'];
        $fileUploaded = "error";

        $labresult = Labresult::create($data);
       
        if(request()->file('file'))
        {
        
            $file = request()->file('file');
            $name = $file->getClientOriginalName();
            $ext = $file->guessClientExtension();

            if(in_array($ext, $mimes))
                $fileUploaded = $file->storeAs("patients/". $id."/labresults/". $labresult->id, $name,'public');

            $labresult->name = $name;
            $labresult->save();
        }

        //return $fileUploaded;
       

        return $labresult;
 
     }
 
     /**
      * Eliminar medicamentos a pacientes
      */
     public function deleteLabResults($id)
     {
       
         $result = Labresult::find($id);
         $result->delete();

         Storage::disk('public')->delete(request('file'));
         
         return '';
 
     }

}
