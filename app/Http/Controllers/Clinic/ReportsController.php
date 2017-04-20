<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Repositories\AppointmentRepository;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
     function __construct(AppointmentRepository $appointmentRepo)
    {
        $this->middleware('auth');
        $this->appointmentRepo = $appointmentRepo;
    }

    /**
     * Mostrar lista de pacientes de un doctor
     */
    public function index()
    {
        return view('clinic.reports.index');
    }

     /**
     * Mostrar lista de pacientes de un doctor
     */
    public function generate()
    {
        
        return $this->statistics_appointments();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function statistics_appointments()
    {
        $search = request()->all();
        $statistics = [];
        
        if($search){

            $search['medic'] = (isset($search['medic'])) ? $search['medic'] : '';
            $search['speciality'] = (isset($search['speciality'])) ? $search['speciality'] : '';
            $search['date1'] = (isset($search['date1'])) ? $search['date1'] : '';
            $search['date2'] = (isset($search['date2'])) ? $search['date2'] : '';

           
            
           
            $statistics = $this->appointmentRepo->reportsStatistics($search);
           
           
            
       }
        
        return $statistics;
    }
}
