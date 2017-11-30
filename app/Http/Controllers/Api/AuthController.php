<?php

namespace App\Http\Controllers\Api;



use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class AuthController extends ApiController
{
    function __construct(UserRepository $userRepo)
    {
        
        $this->userRepo = $userRepo;
       
    }


    public function token(Request $request)
    {
        $error = [
            'error' => 'Unauthenticated'
        ];
       
       if (\Auth::attempt(['phone' => $request->input('phone'), 'password' => $request->input('password')])) {
            // Authentication passed...
            $user = \Auth::user();

            if($user->hasRole('paciente')){

                $data = [
                    'access_token' => $user->api_token,
                    'user' => $user,
                    'patients' => $user->patients->count()
                ];

                \Auth::logout();

                 return json_encode($data);
             }
        }


        
   
        
        return  json_encode($error);

    }

    public function registerSocial(Request $request)
    {
       
        $user = User::where('email',$request->input('email'))->first();
        
        if($user)
        {
            $user->name = $request->input('name');
            $user->push_token = $request->input('push_token');
            $user->save();
        }
    
       
        if(!$user)
        {
            $this->validate($request,[
                'name' => 'required',
                'email' => ['required','email', Rule::unique('users')->ignore(auth()->id()) ],
                
            ]);
           
            $data['name'] = $request->input('name');
            $data['email'] = $request->input('email');

            $data['provider'] = 'social';
            $data['provider_id'] = ($request->input('api_token')) ? substr($request->input('api_token'), 0, 90) : str_random(90);
            $data['role'] = Role::whereName('paciente')->first();
            $data['api_token'] = ($request->input('api_token')) ? substr($request->input('api_token'), 0, 90) : str_random(90);
            $data['push_token'] = $request->input('push_token');
            $user = $this->userRepo->store($data);
        }

        $data = [
            'access_token' => $user->api_token,
            'user' => $user,
            'patients' => $user->patients->count()
        ];
       
        return json_encode($data);

    }

    public function register(Request $request)
    {
        
         $this->validate($request,[
                'name' => 'required',
                'phone' => 'required|unique:users',
                'email' => ['email', Rule::unique('users')],
                'password' => 'required|min:6|confirmed',
            ]);
       
        $data['name'] = $request->input('name');
        $data['email'] = $request->input('email');
        $data['phone'] = $request->input('phone');
        $data['password'] = $request->input('password');
        $data['provider'] = 'email';
        $data['provider_id'] = $request->input('email');
        $data['role'] = Role::whereName('paciente')->first();
        $data['api_token'] = str_random(90);
        $data['push_token'] = $request->input('push_token');    

        $user = $this->userRepo->store($data);
        dd($user);

        $data = [
            'access_token' => $user->api_token,
            'user' => $user,
            'patients' => $user->patients->count()
        ];
       
        return json_encode($data);

    }

      /**
     * Lista de todas las citas de un doctor sin paginar
     */
    public function getPatients($user_id)
    {
        
        $user = User::find($user_id);

        $patients = $user->patients;
        
        return $patients;
        
    }
      /**
     * Lista de todas las citas de un doctor sin paginar
     */
     public function getFirstPatient($user_id)
     {
         
         $user = User::find($user_id);
 
         $patient = $user->patients->first();
         
         return $patient;
         
     }

    

}
