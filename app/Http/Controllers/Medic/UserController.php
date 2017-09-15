<?php

namespace App\Http\Controllers\Medic;



use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Role;
use App\Setting;
use App\Speciality;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    function __construct(UserRepository $userRepo)
    {
    	$this->middleware('auth');
    	$this->userRepo = $userRepo;
    }

    /**
     * Mostrar vista de editar informacion basica del medico
     */
    public function edit()
    {
    	$tab = request('tab');
        $user = auth()->user();
        $assistants = auth()->user()->assistants()->with('clinicsAssistants')->get();

        $specialities = Speciality::all();

    	return view('medic.account',compact('user','specialities','assistants','tab'));

    }

    /**
     * Actualizar informacion basica del medico
     */
    public function update()
    {  
    	
        $this->validate(request(),[
                'name' => 'required',
                'email' => ['required','email', Rule::unique('users')->ignore(auth()->id()) ],
                'medic_code' => 'required'
            ]);

    	$user = $this->userRepo->update(auth()->id(), request()->all());

        flash('Cuenta Actualizada','success');

    	return Redirect('/medic/account/edit');

    }
    public function updateSettings()
    {  
        
        $settings = Setting::where('user_id',auth()->id())->first();
        if($settings)
        {
            $settings->fill(request()->all());
            $settings->save();
        }
        else{
           $settings = auth()->user()->settings()->create(request()->all());  
        }

        return $settings;
        

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

        /*$dd = \DB::table('assistants_offices')->insert(
                ['assistant_id' => $user->id, 'office_id' => $data['office_id']]
            );*/
        $user->clinicsAssistants()->sync([$data['office_id']]);
      
       
        auth()->user()->addAssistant($user);

        //flash('Asistente Registrado','success');

        return $user->load('clinicsAssistants');//Redirect('/medic/account/edit?tab=assistant');

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
     * Actualizar informacion basica del medico
     */
    public function getConsultoriosIndependientes()
    {  
       

        return auth()->user()->offices()->where('type','Consultorio Independiente')->get();


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
}
