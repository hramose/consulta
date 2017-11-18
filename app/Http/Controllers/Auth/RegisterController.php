<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\NewMedic;
use App\Repositories\UserRepository;
use App\Speciality;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/medic/account/edit?tab=clinics';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepo)
    {
        $this->middleware('guest');
        $this->userRepo = $userRepo;
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
        $specialities = Speciality::all();

        return view('auth.register',compact('specialities'));
    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'medic_code' =>'required',
            'ide' =>'required|unique:users'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        /*return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);*/
        $data['active'] = 0; // los medicos estan inactivos por defecto para revision
        $data['provider'] = 'email';
        $data['provider_id'] = $data['email'];
        $data['api_token'] = str_random(50);

        
        $user = $this->userRepo->store($data);

        try {
                        
            \Mail::to($this->administrators)->send(new NewMedic($user));
            
        }catch (\Swift_TransportException $e)  //Swift_RfcComplianceException
        {
            \Log::error($e->getMessage());
        }
        
        

        return $user;


    }
}
