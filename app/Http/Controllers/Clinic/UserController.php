<?php

namespace App\Http\Controllers\Clinic;



use App\Http\Controllers\Controller;
use App\Repositories\MedicRepository;
use App\Repositories\OfficeRepository;
use App\Repositories\PatientRepository;
use App\Repositories\UserRepository;
use App\Speciality;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    function __construct(UserRepository $userRepo, PatientRepository $patientRepo, OfficeRepository $officeRepo, MedicRepository $medicRepo)
    {
    	$this->middleware('auth')->except('profile');
    	$this->userRepo = $userRepo;
        $this->patientRepo = $patientRepo;
        $this->officeRepo = $officeRepo;
        $this->medicRepo = $medicRepo;
    }

    /**
     * Mostrar vista de editar informacion basica del medico
     */
    public function edit()
    {
    	$tab = request('tab');
        $user = auth()->user();

    	return view('users.edit-patient',compact('user','tab'));

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

    	return Redirect('/account/edit');

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

    /**
     * Mostrar vista de todas las consulta(citas) de un doctor
     */
    public function profile($office_id)
    {
               
         $office = $this->officeRepo->findbyId($office_id);
        
         if($office->active) return redirect('/'); //si esta activo no mostrar nada y mandarlo al home

          $medics = $this->medicRepo->findAllByOffice($office->id);
         // dd($medics);
        if(request('medic'))
            $medic = $this->medicRepo->findById(request('medic'));
        else
            $medic = null;

        
        return view('clinic.profile.temp',compact('office','medics','medic'));
    }


    

}
