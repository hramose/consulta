<?php

namespace App\Http\Controllers\Api;



use App\Http\Controllers\Controller;
use App\Repositories\PatientRepository;
use App\Repositories\UserRepository;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends ApiController
{
    function __construct(UserRepository $userRepo, PatientRepository $patientRepo)
    {
    	$this->middleware('auth');
    	$this->userRepo = $userRepo;
        $this->patientRepo = $patientRepo;
    }


    /**
     * Actualizar informacion basica del medico
     */
    public function update()
    {  
        $user = request()->user();

        $this->validate(request(),[
                'name' => 'required',
                'email' => ['required','email', Rule::unique('users')->ignore($user->id) ]
            ]);

    	$user = $this->userRepo->update($user->id, request()->all());

       

    	return $user;

    }

    /**
     * Guardar avatar del medico
     */
    public function avatars()
    {
       
        $mimes = ['jpg','jpeg','bmp','png'];
        $fileUploaded = "error";
      
        if(request()->file('photo'))
        {
        
            $file = request()->file('photo');
            $ext = $file->getClientOriginalExtension();//guessClientExtension();
            dd($ext);
            if(in_array($ext, $mimes))
                $fileUploaded = $file->storeAs("avatars/". request()->user()->id, "avatar.jpg",'public');
        }

        return $fileUploaded;

    }

    

     /**
     * Actualizar datos de paciente
     */
    public function updatePatient($id)
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
     * Actualizar datos de paciente
     */
    public function destroyPatient($id)
    {
       

      
         $patient = $this->patientRepo->delete($id);

        //($patient === true) ? flash('Paciente eliminado correctamente!','success') : flash('No se puede eliminar paciente por que tiene citas asignadas','error');

       $result = ($patient === true) ? 'ok' : $patient;
        

       

        return $result;

    }

}
