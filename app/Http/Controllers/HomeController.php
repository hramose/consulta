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
        $search['date'] = Carbon::today()->toDateTimeString();
        $appointments =$this->appointmentRepo->findAllByDoctor(auth()->id(), $search, 5);
       
        if(auth()->user()->hasRole('paciente'))
            return view('home-patient',compact('appointments'));
       
        return view('home',compact('appointments'));

       
    }

    
}
