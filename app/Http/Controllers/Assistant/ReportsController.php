<?php

namespace App\Http\Controllers\Assistant;

use App\Http\Controllers\Controller;
use App\Repositories\AppointmentRepository;
use App\Repositories\ClinicRepository;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
     function __construct(AppointmentRepository $appointmentRepo, ClinicRepository $clinicRepo)
    {
        $this->middleware('authByRole:asistente');
        $this->appointmentRepo = $appointmentRepo;
        $this->clinicRepo = $clinicRepo;
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
        if(request('type') == 'Evaluación de usuario')
            return $this->statistics_polls();
        else
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

    public function statistics_polls()
    {
        $search = request()->all();
        $statistics = [];
        
        if($search){

            $search['medic'] = (isset($search['medic'])) ? $search['medic'] : '';
            $search['reviewType'] = (isset($search['reviewType'])) ? $search['reviewType'] : '';
            $search['clinic'] = (isset($search['clinic'])) ? $search['clinic'] : '';
            $search['date1'] = (isset($search['date1'])) ? $search['date1'] : '';
            $search['date2'] = (isset($search['date2'])) ? $search['date2'] : '';

           
            
           
            $statistics = $this->clinicRepo->reportsStatistics($search);
           
           
            
       }
        
        return $statistics;
    }
}
