<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;

use App\Repositories\UserRepository;
use App\Role;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\PharmacyRepository;

class RegisterController extends Controller
{
    public function __construct(UserRepository $userRepo, PharmacyRepository $pharmacyRepo)
    {
        //$this->middleware('auth')->except('profile');
        $this->userRepo = $userRepo;
        $this->pharmacyRepo = $pharmacyRepo;

        $this->administrators = User::whereHas('roles', function ($query) {
            $query->where('name', 'administrador');
        })->get();
    }

    /**
    * Show the application registration form.
    *
    * @return \Illuminate\Http\Response
    */
    public function showRegistrationForm()
    {
        return view('auth.register-pharmacy');
    }

    /**
    * Show the application registration form.
    *
    * @return \Illuminate\Http\Response
    */
    public function showRegistrationPharmacyForm()
    {
        return view('pharmacy.register');
    }

    protected function registerAdmin()
    {
        $this->validate(request(), [
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|min:6|confirmed'
        ]);

        $data = request()->all();

        $data['active'] = 0; // las clinicas estan inactivos por defecto para revision
        $data['role'] = Role::whereName('farmacia')->first();
        $data['provider'] = 'email';
        $data['provider_id'] = $data['email'];
        $data['api_token'] = str_random(50);

        $user = $this->userRepo->store($data);

        Auth::login($user);

        return Redirect('/pharmacy/register/pharmacy');
    }

    protected function registerPharmacy()
    {
        $this->validate(request(), [
                'name' => 'required',
                'address' => 'required',
                'province' => 'required',
                'canton' => 'required',
                'district' => 'required',
                'phone' => 'required',
                'ide' => 'required',
                'ide_name' => 'required',
        ]);

        $data = request()->all();
        $data['active'] = 0;
        $data['notification'] = 1;
        $data['notification_date'] = Carbon::now()->toDateTimeString();

        //if($data['type'] == 'Consultorio Independiente') $data['active'] = 1;

        $pharmacy = $this->pharmacyRepo->store($data);

        $mimes = ['jpg', 'jpeg', 'bmp', 'png'];
        $fileUploaded = 'error';

        if (request()->file('file')) {
            $file = request()->file('file');

            $ext = $file->guessClientExtension();

            if (in_array($ext, $mimes)) {
                $fileUploaded = $file->storeAs('pharmacies/' . $pharmacy->id, 'photo.jpg', 'public');
            }
        }

        $adminPharmacy = auth()->user();

        /*try {
            \Mail::to($this->administrators)->send(new NewPharmacy($adminPharmacy, $pharmacy));
        } catch (\Swift_TransportException $e) {  //Swift_RfcComplianceException
            \Log::error($e->getMessage());
        }*/

        return Redirect('/');
    }
}
