<?php

namespace App\Http\Controllers\Clinic;



use App\Http\Controllers\Controller;
use App\Repositories\MedicRepository;
use App\Repositories\OfficeRepository;
use App\Repositories\PatientRepository;
use App\Repositories\UserRepository;
use App\Role;
use App\Speciality;
use App\User;
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
    
       $assistants = auth()->user()->assistants()->with('clinicsAssistants')->get();

    	return view('clinic.profile.edit',compact('user','assistants','tab'));

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

    	return Redirect('/clinic/account/edit');

    }

    /**
     * Actualizar informacion basica del medico
     */
    public function addAssistant()
    {  

        $this->validate(request(),[
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'office_id' => 'required',
            ]);

        $data = request()->all();
      
       
    
        $data['role'] = Role::whereName('asistente')->first();
        $data['provider'] = 'email';
        $data['provider_id'] = $data['email'];


        $user = $this->userRepo->store($data);

      
        $user->clinicsAssistants()->sync([$data['office_id']]);
      
       
        auth()->user()->addAssistant($user);

        //flash('Asistente Registrado','success');

        return $user->load('clinicsAssistants');//Redirect('/medic/account/edit?tab=assistant');
      
       /*$this->validate(request(),[
                'name' => 'required',
                'email' => ['required','email', Rule::unique('users')->ignore(request('assistant_id')) ]
            ]);
        
        $data = request()->all();

        if(request('assistant_id'))
       {

            $assistant = $this->userRepo->update(request('assistant_id'), $data);
          
             flash('Asistente actualizado','success');

            return Redirect('/clinic/account/edit?tab=assistant');

       }
       
    
        $data['role'] = Role::whereName('asistente')->first();
        $data['provider'] = 'email';
        $data['provider_id'] = $data['email'];


        $user = $this->userRepo->store($data);
      
       
        auth()->user()->addAssistant($user);

        flash('Asistente Registrado','success');

        return Redirect('/clinic/account/edit?tab=assistant');*/

    }

    /**
     * Actualizar informacion basica del medico
     */
    public function updateAssistant($id)
    {  
       
      
       $this->validate(request(),[
                'name' => 'required',
                'email' => ['required','email', Rule::unique('users')->ignore($id) ],
                'office_id' => 'required',
            ]);
       
        $data = request()->all();
       

        $assistant = $this->userRepo->update($id, $data);
        
        $assistant = $assistant->clinicsAssistants()->sync([$data['office_id']]);
      
  
        return $assistant;

      


    }
    /**
     * Actualizar informacion basica del medico
     */
    public function deleteAssistant($id)
    {  
      
       $assistant = User::find($id);

       auth()->user()->removeAssistant($assistant);

       $assistant->delete();
       
  
        return 'ok';

      


    }
    /**
     * Actualizar informacion basica del medico
     */
    public function getAssistants()
    {  
       
  
        return auth()->user()->assistants()->with('clinicsAssistants')->get();


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
        
         if($office->active) return redirect('/clinic/appointments'); //si esta activo no mostrar nada y mandarlo a la agenda

          $medics = $this->medicRepo->findAllByOffice($office->id);
         // dd($medics);
        if(request('medic'))
            $medic = $this->medicRepo->findById(request('medic'));
        else
            $medic = null;

        
        return view('clinic.profile.temp',compact('office','medics','medic'));
    }

    /**
     * Actualizar datos de consultorio
     */
    public function updateClinic($id)
    {
       
         $this->validate(request(),[
                'type' => 'required',
                'name' => 'required',
                'address' => 'required',  
                'province' => 'required',  
                'canton' => 'required', 
                'district' => 'required',  
                'phone' => 'required',      
        ]);
         
         $data = request()->all();

        $office = $this->officeRepo->update($id, $data);

        //flash('Consultorio Actualizado','success');

        return $office;

    }
    public function updateOfficeNotification($id)
    {
      
        $data = request()->all();
      

        $office = $this->officeRepo->update($id,  $data);

        return $office;
        

    }

     public function getSpecialities()
    {
        $search = request()->all();
        $specialities = Speciality::where('name', 'like', '%' . $search['q'] . '%')->get();

        return $specialities;
    }


    

}
