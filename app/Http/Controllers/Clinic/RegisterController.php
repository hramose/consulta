<?php

namespace App\Http\Controllers\Clinic;



use App\Http\Controllers\Controller;
use App\Mail\NewClinic;
use App\Repositories\OfficeRepository;
use App\Repositories\UserRepository;
use App\Role;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    function __construct(UserRepository $userRepo, OfficeRepository $officeRepo)
    {
    	//$this->middleware('auth')->except('profile');
    	$this->userRepo = $userRepo;
        $this->officeRepo = $officeRepo;

        $this->administrators = User::whereHas('roles', function ($query){
                        $query->where('name',  'administrador');
                    })->get();
        
    }

     /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {

        return view('auth.register-clinic');
    }

     /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationOfficeForm()
    {

        return view('clinic.register');
    }

    protected function registerAdmin()
    {
        $data = request()->all();


        $data['active'] = 0; // las clinicas estan inactivos por defecto para revision
        $data['role'] = Role::whereName('clinica')->first();
        $data['provider'] = 'email';
        $data['provider_id'] = $data['email'];
        $data['api_token'] = str_random(50);

        $user = $this->userRepo->store($data);

        Auth::login($user);

        return Redirect('/clinic/register/office');
        
      
    }

    protected function registerOffice()
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
        $data['type'] = 'Clínica Privada';
        $data['active'] = 1;
        $data['notification'] = 1;
        $data['notification_date'] = Carbon::now()->toDateTimeString();
        
        //if($data['type'] == 'Consultorio Independiente') $data['active'] = 1;

        $office = $this->officeRepo->store($data);

        
        $adminClinic = auth()->user();
       
        
        try {
                        
            \Mail::to($this->administrators)->send(new NewClinic($adminClinic,$office));
            
        }catch (\Swift_TransportException $e)  //Swift_RfcComplianceException
        {
            \Log::error($e->getMessage());
        }

        

        return Redirect('/');


    }

    
    

}
