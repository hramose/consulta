<?php

namespace App\Http\Controllers\Api;



use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Role;
use App\User;
use Illuminate\Http\Request;


class AuthController extends ApiController
{
    function __construct(UserRepository $userRepo)
    {
        
        $this->userRepo = $userRepo;
       
    }


    public function token(Request $request)
    {
       
       if (\Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
            // Authentication passed...
            $user = \Auth::user();

            $data = [
                'access_token' => $user->api_token,
                'user' => $user
            ];

            \Auth::logout();

             return json_encode($data);
        }

        
        $error = [
            'error' => 'Unauthenticated'
        ];
   
        
        return  json_encode($error);

    }

    public function register(Request $request)
    {
       
        $user = User::where('email',$request->input('email'))->first();
        
        if($user)
        {
            $user->name = $request->input('name');
            $user->save();
        }
    
       
        if(!$user)
        {
            /*$user = \User::create([
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'provider' => 'social',
                    'provider_id' => ($request->input('api_token')) ? substr($request->input('api_token'), 0, 90) : str_random(90),
                    'password' =>'',
                    'api_token' => ($request->input('api_token')) ? substr($request->input('api_token'), 0, 90) : str_random(90)
                ]);*/
            $data['name'] = $request->input('name');
            $data['email'] = $request->input('email');

            $data['provider'] = 'social';
            $data['provider_id'] = str_random(10);
            $data['role'] = Role::whereName('paciente')->first();
            $data['api_token'] = str_random(90);
            
            $user = $this->userRepo->store($data);
        }

        $data = [
            'access_token' => $user->api_token,
            'user' => $user
        ];
       
        return json_encode($data);

    }

    

}
