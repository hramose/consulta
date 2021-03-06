<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\AppointmentRepository;
use App\Repositories\ClinicRepository;
use App\Repositories\IncomeRepository;
use App\Repositories\MedicRepository;
use App\Repositories\PatientRepository;
use App\User;
use App\Patient;

use Illuminate\Http\Request;

class ReportsController extends Controller
{
     function __construct(AppointmentRepository $appointmentRepo, ClinicRepository $clinicRepo, MedicRepository $medicRepo, PatientRepository $patientRepo, IncomeRepository $incomeRepo)
    {
        $this->middleware('auth');
        $this->appointmentRepo = $appointmentRepo;
        $this->clinicRepo = $clinicRepo;
        $this->medicRepo = $medicRepo;
        $this->patientRepo = $patientRepo;
         $this->incomeRepo = $incomeRepo;
    }

    /**
     * Mostrar lista de pacientes de un doctor
     */
    public function index()
    {
        $medics =  User::whereHas('roles', function($q){
                    $q->where('name', 'medico');
                })->count();

        $clinics = User::whereHas('roles', function($q){
                    $q->where('name', 'clinica');
                })->count();
                
        $patients = Patient::count();

        return view('admin.reports.index', compact('medics','clinics','patients'));
    }


    /**
     * Mostrar lista de pacientes de un doctor
     */
    public function medics()
    {
        return view('admin.reports.medics');
    }

    /**
     * Mostrar lista de pacientes de un doctor
     */
    public function clinics()
    {
        return view('admin.reports.clinics');
    }

    /**
     * Mostrar lista de pacientes de un doctor
     */
    public function patients()
    {
        return view('admin.reports.patients');
    }
    
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generateIncomes()
    {
        $search = request()->all();
        $statistics = [];
        
        if($search){

    
            $search['date1'] = (isset($search['date1'])) ? $search['date1'] : '';
            $search['date2'] = (isset($search['date2'])) ? $search['date2'] : '';

          
         
            $statistics = $this->incomeRepo->reportsStatistics($search);
           
            
       }
        
        return $statistics;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generateMedics()
    {
        $search = request()->all();
        $statistics = [];
        
        if($search){

            $search['medic'] = (isset($search['medic'])) ? $search['medic'] : '';
            $search['date1'] = (isset($search['date1'])) ? $search['date1'] : '';
            $search['date2'] = (isset($search['date2'])) ? $search['date2'] : '';

          
            
            if($search['medic'])
                $statistics = $this->medicRepo->reportsStatisticsByMedic($search);
            else
                $statistics = $this->medicRepo->reportsStatistics($search);
           
            
       }
        
        return $statistics;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generateClinics()
    {
        $search = request()->all();
        $statistics = [];
        
        if($search){

            $search['medic'] = (isset($search['medic'])) ? $search['medic'] : '';
            $search['clinic'] = (isset($search['clinic'])) ? $search['clinic'] : '';
            $search['date1'] = (isset($search['date1'])) ? $search['date1'] : '';
            $search['date2'] = (isset($search['date2'])) ? $search['date2'] : '';

          
            
            $statistics = $this->clinicRepo->reportsStatisticsAppointments($search);
           
            
       }
        
        return $statistics;
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generatePatients()
    {
        $search = request()->all();
        $statistics = [];
        
        if($search){

            
            $search['date1'] = (isset($search['date1'])) ? $search['date1'] : '';
            $search['date2'] = (isset($search['date2'])) ? $search['date2'] : '';

          
            
            $statistics = $this->patientRepo->reportsStatistics($search);
           
            
       }
        
        return $statistics;
    }

    
}
