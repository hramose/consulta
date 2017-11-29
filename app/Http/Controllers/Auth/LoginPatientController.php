<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\ResetCode;
use Illuminate\Validation\ValidationException;

class LoginPatientController extends Controller
{
    use ValidatesRequests;

    public function login()
    {
        return view('auth.login-patient');
    }

    public function validateRequest(Request $request)
    {
         $this->validate($request, [
            'phone' => 'required|exists:users'
        ]);

        return $this;

    }

    public function postLogin(Request $request)
    {
      $v = $this->validateRequest($request);

        if (Auth::attempt(['phone' => $request->phone, 'password' => $request->password] ,$request->has('remember'))) {
            // Authentication passed...
            return redirect()->intended('/');
        }

        throw ValidationException::withMessages([
            'phone' => [trans('auth.failed')],
        ]);

       
        return back();


    }

    public function resetPassword()
    {
        return view('auth.passwords.phone');

    }

    public function sendResetCodePhone(Request $request)
    {
        $this->validatePhone($request);

        $user = User::byPhone($request->phone);

        $code = ResetCode::generateFor($user);

        $code->send();

        return redirect('/user/password/reset/code');

    }

    public function resetCode()
    {
        return view('auth.passwords.code');

    }

    public function newPassword(Request $request)
    {
         $this->validate($request, [
             'phone' => 'required|exists:users',
             'password' =>'required|confirmed',
             'code' => 'required|exists:reset_codes'

        
             ]);

          $code = ResetCode::where('code',$request->code)->first();

          
          $user = $code->user;

          $user->password = bcrypt($request->password);
          $user->save();


          Auth::login($user);

         $code->delete();


        return redirect('/');
;

    }

     protected function validatePhone(Request $request)
    {
        $this->validate($request, ['phone' => 'required|exists:users']);
    }
}
