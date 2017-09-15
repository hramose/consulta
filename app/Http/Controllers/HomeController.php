<?php

namespace App\Http\Controllers;

use App\Repositories\AppointmentRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AppointmentRepository $appointmentRepo)
    {
        $this->middleware('auth');
        $this->appointmentRepo = $appointmentRepo;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //dd(Carbon::now()->toDateTimeString());

        if(auth()->user()->hasRole('administrador'))
            return view('admin.home');
        
        if(auth()->user()->hasRole('paciente'))
            return view('user.home');

         if(auth()->user()->hasRole('clinica')){
            
            if(!auth()->user()->offices->count()) return Redirect('/clinic/register/office');
            
            return view('clinic.home');
        }

        if(auth()->user()->hasRole('asistente'))
            return view('assistant.home');


        $search['date'] = Carbon::today()->toDateTimeString();
        $appointments =$this->appointmentRepo->findAllByDoctor(auth()->id(), $search, 5);
       
       
        return view('medic.home',compact('appointments'));

       
    }

    
}
