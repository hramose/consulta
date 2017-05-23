<?php

namespace App\Http\Controllers\Medic;



use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Role;
use App\Setting;
use App\Speciality;
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
        $assistant = auth()->user()->assistants->first();
        $specialities = Speciality::all();

    	return view('users.edit',compact('user','specialities','assistant','tab'));

    }

    /**
     * Actualizar informacion basica del medico
     */
    public function update()
    {  
    	
        $this->validate(request(),[
                'name' => 'required',
                'email' => ['required','email', Rule::unique('users')->ignore(auth()->id()) ],
                'speciality' => 'required'
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
                'email' => ['required','email', Rule::unique('users')->ignore(request('assistant_id')) ]
            ]);

       $data = request()->all();
       
       if(request('assistant_id'))
       {

            $assistant = $this->userRepo->update(request('assistant_id'), $data);
          
             flash('Asistente actualizado','success');

            return Redirect('/medic/account/edit?tab=assistant');

       }
        
       
    
        $data['role'] = Role::whereName('asistente')->first();
        $data['provider'] = 'email';
        $data['provider_id'] = $data['email'];


        $user = $this->userRepo->store($data);
      
       
        auth()->user()->addAssistant($user);

        flash('Asistente Registrado','success');

        return Redirect('/medic/account/edit?tab=assistant');

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
