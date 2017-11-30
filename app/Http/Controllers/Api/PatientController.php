<?php

namespace App\Http\Controllers\Api;

use App\Allergy;
use App\Appointment;
use App\Http\Requests\PatientRequest;
use App\Labexam;
use App\Labresult;
use App\Pressure;
use App\Repositories\AppointmentRepository;
use App\Repositories\PatientRepository;
use App\Repositories\UserRepository;
use App\Sugar;
use Carbon\Carbon;
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
    public function __construct(PatientRepository $patientRepo)
    {
       
        $this->patientRepo = $patientRepo;

        
    }



    /**
     * Guardar paciente
     */
    public function store(PatientRequest $request)
    {
      
        $patient =$this->patientRepo->store($request->all(),$request->user());



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
                'address' => 'required',  
                'province' => 'required',  
                'city' => 'required', 
                'phone' => ['required', Rule::unique('patients')->ignore($id) ],
                'email' => ['email', Rule::unique('patients')->ignore($id) ]//'required|email|max:255|unique:patients',   
        ]);

        $patient = $this->patientRepo->update($id, request()->all());
        

        return $patient;

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
    public function allergies($id)
    {
        $patient = $this->patientRepo->findById($id);
        $history = $patient->history;

        $data = request()->all();

        $data['user_id'] = request()->user()->id;

        $data['history_id'] = $history->id;
       
        $allergy = Allergy::create($data);
        $allergy->load('user.roles');
     
        return $allergy;
    
   

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
     * Eliminar medicamentos a pacientes
     */
    public function deleteAllergies($id)
    {
      
       
        $allergy = Allergy::find($id);

       if(! $allergy)
         {
             return $this->respondNotFound('Alergia does not exist');
 
         }

         $allergy->delete($id);
         
         return $this->respondDeleted('Alergia Eliminada Correctamente');

    }

      /**
     * Agregar medicamentos a pacientes
     */
    public function pressures($id)
    {
        $this->validate(request(),[
                'ps' => 'required|numeric',
                'pd' => 'required|numeric',
                'date_control' => 'required',
                'time_control' => 'required'
                
            ]);

        $patient = $this->patientRepo->findById($id);
        $pressures = $patient->pressures()->create(request()->all());

       
     
        return $pressures;
    
   

    }
      /**
     * get pressures a pacientes
     */
    public function getPressures($id)
    {
    

        $patient = $this->patientRepo->findById($id);
        $pressures = $patient->pressures()->orderBy('created_at','DESC')->limit(10)->get();

       
     
        return $pressures;
    
   

    }

    /**
     * Eliminar medicamentos a pacientes
     */
    public function deletePressures($id)
    {
      
       $pressure = Pressure::find($id);

       if(! $pressure)
         {
             return $this->respondNotFound('Control does not exist');
 
         }

         $pressure->delete($id);
         
         return $this->respondDeleted('Control Eliminado Correctamente');
      
        
      

    }

       /**
     * Agregar medicamentos a pacientes
     */
    public function sugars($id)
    {
        $this->validate(request(),[
                'glicemia' => 'required|numeric',
                'date_control' => 'required',
                'time_control' => 'required'
                
            ]);

        $patient = $this->patientRepo->findById($id);
        $sugars = $patient->sugars()->create(request()->all());

       
     
        return $sugars;
    
   

    }
      /**
     * get pressures a pacientes
     */
    public function getSugars($id)
    {
    

        $patient = $this->patientRepo->findById($id);
        $sugars = $patient->sugars()->orderBy('created_at','DESC')->limit(10)->get();

       
     
        return $sugars;
    
   

    }

    /**
     * Eliminar medicamentos a pacientes
     */
    public function deleteSugars($id)
    {
      
      $sugar = Sugar::find($id);

       if(! $sugar)
         {
             return $this->respondNotFound('Control does not exist');
 
         }

         $sugar->delete($id);
         
         return $this->respondDeleted('Control Eliminado Correctamente');

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
       $labexams =  $patient->labexams()->limit(10)->get();

       $labexams = $labexams->groupBy(function($exam) {
            return Carbon::parse($exam->date)->toDateString();
        })->toArray();

        $dataExams = [];
        $exam = [];

        foreach ($labexams as $key => $value) {
        
            $exam['date'] = $key;
            $exam['exams'] = $value;
            $dataExams[]= $exam;
        }
       


        $data = [
            'history' => $history,
            'appointments'=> $appointments,
            'labresults'=> $labresults,
            'labexams'=> $dataExams
            
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
            return Carbon::parse($exam->date)->toDateString();
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
