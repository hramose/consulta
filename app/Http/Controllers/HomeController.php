<?php

namespace App\Http\Controllers;

use App\Repositories\AppointmentRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\User;
use App\Mail\NewContact;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AppointmentRepository $appointmentRepo)
    {
        $this->middleware('auth')->except('firma');
        $this->appointmentRepo = $appointmentRepo;
        $this->administrators = User::whereHas('roles', function ($query) {
            $query->where('name', 'administrador');
        })->get();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //dd(Carbon::now()->toDateTimeString());

        if (auth()->user()->hasRole('administrador')) {
            return view('admin.home');
        }

        if (auth()->user()->hasRole('paciente')) {
            return view('user.home');
        }

        if (auth()->user()->hasRole('clinica')) {
            if (!auth()->user()->offices->count()) {
                return Redirect('/clinic/register/office');
            }

            return view('clinic.home');
        }

        if (auth()->user()->hasRole('asistente')) {
            return view('assistant.home');
        }

        $search['date'] = Carbon::today()->toDateTimeString();
        $appointments = $this->appointmentRepo->findAllByDoctor(auth()->id(), $search, 5);


        if( !session()->has('office_id') || session('office_id') == '' ){
            
            if(auth()->user()->offices->count())
                session(['office_id' => auth()->user()->offices->first()->id]);

        }
         

        return view('medic.home', compact('appointments'));
    }

    public function changeOffice()
    {
        // Store a piece of data in the session...
        session(['office_id' => request('selected_clinic')]);

        return back();
    }

    public function support()
    {
        $dataMessage = request()->all();

        $dataMessage['user'] = auth()->user();

        try {
            \Mail::to($this->administrators)->send(new NewContact($dataMessage));

            return 'ok';
        } catch (\Swift_TransportException $e) {  //Swift_RfcComplianceException
            \Log::error($e->getMessage());
        }
    }
    public function firma(){
        return 'firma';
    }
}
