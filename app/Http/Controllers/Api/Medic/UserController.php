<?php

namespace App\Http\Controllers\Api\Medic;



use App\Http\Controllers\Api\ApiController;
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
    public function edit()
    {  
        $user = request()->user();
       
        return $user->load('settings','specialities');

    }

    /**
     * Actualizar informacion basica del medico
     */
    public function update()
    {  
        $user = request()->user();

        $this->validate(request(),[
                'name' => 'required',
                'email' => ['required','email', Rule::unique('users')->ignore($user->id) ],
                'medic_code' => 'required'
            ]);

    	$user = $this->userRepo->update($user->id, request()->all());

       

    	return $user->load('settings','specialities');

    }
    /**
     * Actualizar informacion basica del medico
     */
     public function updatePushToken()
     {  
         $user = request()->user();

 
         $user->push_token = request('push_token'); // = $this->userRepo->update($user->id, request()->all());
         $user->save();
        
 
         return $user->load('settings');
 
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
            
            if(in_array($ext, $mimes))
                $fileUploaded = $file->storeAs("avatars/". request()->user()->id, "avatar.jpg",'public');
        }

        return $fileUploaded;

    }

    


}
