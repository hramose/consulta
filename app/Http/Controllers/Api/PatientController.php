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
     * Eliminar medicamentos a pacientes
     */
    public function deleteMedicines($id)
    {
      
        $medicine = $this->patientRepo->deleteMedicine($id);
        
        return '';

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
     * Eliminar medicamentos a pacientes
     */
    public function deleteAllergies($id)
    {
      
       $allergy = Allergy::findOrFail($id)->delete($id);

     
      
        
        return '';

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
      
       $allergy = Pressure::findOrFail($id)->delete($id);

     
      
        
        return '';

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
     * Eliminar medicamentos a pacientes
     */
    public function deleteSugars($id)
    {
      
       $sugar = Sugar::findOrFail($id)->delete($id);

     
      
        
        return '';

    }
}
