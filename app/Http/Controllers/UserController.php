<?php

namespace App\Http\Controllers;



use App\Http\Controllers\Controller;
use App\Repositories\PatientRepository;
use App\Repositories\UserRepository;
use App\Speciality;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    function __construct(UserRepository $userRepo, PatientRepository $patientRepo)
    {
    	$this->middleware('auth');
    	$this->userRepo = $userRepo;
        $this->patientRepo = $patientRepo;
    }

    /**
     * Mostrar vista de editar informacion basica del medico
     */
    public function edit()
    {
    	
        $user = auth()->user();

    	return view('users.edit-patient',compact('user'));

    }

    /**
     * Actualizar informacion basica del medico
     */
    public function update()
    {  
    	
        $this->validate(request(),[
                'name' => 'required',
                'email' => ['required','email', Rule::unique('users')->ignore(auth()->id()) ]
            ]);

    	$user = $this->userRepo->update(auth()->id(), request()->all());

        flash('Cuenta Actualizada','success');

    	return Redirect('/medic/account/edit');

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
            $ext = $file->guessClientExtension();
           
            if(in_array($ext, $mimes))
                $fileUploaded = $file->storeAs("avatars/". auth()->id(), "avatar.jpg",'public');
        }

        return $fileUploaded;

    }

    public function storePatient()
    {
        
        $this->validate(request(),[
                'first_name' => 'required',
                'last_name' => 'required',
                'address' => 'required',  
                'province' => 'required',  
                'city' => 'required', 
                'phone' => 'required',
                'email' => 'required|email',

        ]);

        $data = request()->all();
        
      

        $patient = $this->patientRepo->store($data);


        return $patient;
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
                'email' => 'required|email',   
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

       

        return '';

    }

}
