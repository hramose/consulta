<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\NewMedic;
use App\Repositories\UserRepository;
use App\Role;
use App\User;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;


class AuthController extends Controller
{
   
  protected $redirectTo = '/home';//'/medic/account/edit?tab=clinics';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepo)
    {
        $this->middleware('guest', ['except' => 'logout']);
        $this->userRepo = $userRepo;
        $this->administrators = User::whereHas('roles', function ($query){
                        $query->where('name',  'administrador');
                    })->get();
    }

     public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from provider.  Check if the user already exists in our
     * database by looking up their provider_id in the database.
     * If the user exists, log them in. Otherwise, create a new user then log them in. After that 
     * redirect them to the authenticated users homepage.
     *
     * @return Response
     */
    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->user();
        
        $authUser = $this->findOrCreateUser($user, $provider);
        Auth::login($authUser, true);

        return redirect($this->redirectTo);
    }

    /**
     * If a user has registered before using social auth, return the user
     * else, create a new user object.
     * @param  $user Socialite user object
     * @param $provider Social auth provider
     * @return  User
     */
    public function findOrCreateUser($user, $provider)
    {
        $authUser = User::where('provider_id', $user->id)->first();
        
        if ($authUser) {
            return $authUser;
        }

        $authUser = User::where('email', $user->email)->first();
        if ($authUser) {
            return $authUser;
        }

        $data['name'] = $user->name;
        $data['email'] = $user->email;
        //$data['active'] = 0; // los medicos estan inactivos por defecto para revision
        $data['provider'] = $provider;
        $data['provider_id'] = $user->id;
        //$data['speciality'] = [53];
        $data['role'] = Role::whereName('paciente')->first();
        
        $user = $this->userRepo->store($data);

        

        //\Mail::to($this->administrators)->send(new NewMedic($user));

        return $user;

        /*return User::create([
            'name'     => $user->name,
            'email'    => $user->email,
            'provider' => $provider,
            'provider_id' => $user->id
        ]);*/
    }

    

}
