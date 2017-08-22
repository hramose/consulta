<?php

namespace App\Http\Controllers\Api;

use App\Allergy;
use App\Http\Requests\PatientRequest;
use App\Pressure;
use App\Repositories\PatientRepository;
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
                'phone' => 'required',
                'email' => ['required','email', Rule::unique('patients')->ignore($id) ]//'required|email|max:255|unique:patients',   
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
        
       return  $medicine;

    }
     /**
     * Agregar medicamentos a pacientes
     */
    public function getMedicines($id)
    {
       $patient = $this->patientRepo->findById($id);
       $medicines = $patient->medicines;
        
       return  $medicines;

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
       $allergies = $patient->allergies;
        
       return  $allergies;

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
        $pressures = $patient->pressures;

       
     
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
        $sugars = $patient->sugars;

       
     
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
}
