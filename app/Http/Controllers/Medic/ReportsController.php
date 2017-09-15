<?php

namespace App\Http\Controllers\Medic;

use App\Http\Controllers\Controller;
use App\Repositories\AppointmentRepository;
use App\Repositories\MedicRepository;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
     function __construct(AppointmentRepository $appointmentRepo, MedicRepository $medicRepo)
    {
        $this->middleware('auth');
        $this->appointmentRepo = $appointmentRepo;
        $this->medicRepo = $medicRepo;
    }

    /**
     * Mostrar lista de pacientes de un doctor
     */
    public function index()
    {
        return view('medic.reports.index');
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

           
            
           
            $statistics = $this->medicRepo->reportsStatisticsReviews($search);
           
           
            
       }
        
        return $statistics;
    }
}
