<?php

namespace App\Http\Controllers\Pharmacy;



use App\Http\Controllers\Controller;
use App\Repositories\MedicRepository;
use App\Repositories\PatientRepository;
use App\Repositories\UserRepository;
use App\Role;
use App\Speciality;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Repositories\PharmacyRepository;

class UserController extends Controller
{
    function __construct(UserRepository $userRepo, PatientRepository $patientRepo, PharmacyRepository $pharmacyRepo)
    {
    	$this->middleware('authByRole:farmacia')->except('profile');
    	$this->userRepo = $userRepo;
        $this->patientRepo = $patientRepo;
         $this->pharmacyRepo = $pharmacyRepo;

       
    }

    /**
     * Mostrar vista de editar informacion basica del medico
     */
    public function edit()
    {
    	 $tab = request('tab');
       $user = auth()->user();
    
       $assistants = auth()->user()->assistants()->with('pharmaciesAssistants')->get();
       $pharmacy = auth()->user()->pharmacies->first();

      

    	return view('pharmacy.profile.edit',compact('user','assistants','tab'));

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

    	return Redirect('/pharmacy/account/edit');

    }

    /**
     * Actualizar informacion basica del medico
     */
    public function addAssistant()
    {  

        $this->validate(request(),[
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'pharmacy_id' => 'required',
            ]);

        $data = request()->all();
      
       
    
        $data['role'] = Role::whereName('asistente')->first();
        $data['provider'] = 'email';
        $data['provider_id'] = $data['email'];


        $user = $this->userRepo->store($data);

      
        $user->pharmaciesAssistants()->sync([$data['pharmacy_id']]);
      
       
        auth()->user()->addAssistant($user);

        //flash('Asistente Registrado','success');

        return $user->load('pharmaciesAssistants');//Redirect('/medic/account/edit?tab=assistant');
      
       

    }

    /**
     * Actualizar informacion basica del medico
     */
    public function updateAssistant($id)
    {  
       
      
       $this->validate(request(),[
                'name' => 'required',
                'email' => ['required','email', Rule::unique('users')->ignore($id) ],
                'pharmacy_id' => 'required',
            ]);
       
        $data = request()->all();
       

        $assistant = $this->userRepo->update($id, $data);
        
        $assistant = $assistant->pharmaciesAssistants()->sync([$data['pharmacy_id']]);
      
  
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
       
  
        return auth()->user()->assistants()->with('pharmaciesAssistants')->get();


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
    public function profile($pharmacy_id)
    {
               
         $pharmacy = $this->pharmacyRepo->findbyId($pharmacy_id);
        
         if($pharmacy->active) return redirect('/pharmacy/appointments'); //si esta activo no mostrar nada y mandarlo a la agenda

         

        
        return view('pharmacy.profile.temp',compact('pharmacy','medics','medic'));
    }

    /**
     * Actualizar datos de consultorio
     */
    public function updatePharmacy()
    {
       
         $this->validate(request(),[
                'name' => 'required',
                'address' => 'required',  
                'province' => 'required',  
                'canton' => 'required', 
                'district' => 'required',  
                'phone' => 'required',      
        ]);
         
         $data = request()->all();

         if(isset($data['id']) && $data['id']){ // update
            
            $pharmacy = $this->pharmacyRepo->update($data['id'], $data);
            
            $mimes = ['jpg','jpeg','bmp','png'];
            $fileUploaded = "error";
        
            if(request()->file('file'))
            {
            
                $file = request()->file('file');

                $ext = $file->guessClientExtension();

                if(in_array($ext, $mimes))
                    $fileUploaded = $file->storeAs("pharmacies/". $pharmacy->id, "photo.jpg",'public');
            }

            
            return $pharmacy;
            
        }
        



    }
    public function updatePharmacyNotification($id)
    {
      
        $data = request()->all();
      

        $pharmacy = $this->pharmacyRepo->update($id,  $data);

        return $pharmacy;
        

    }

    

}
