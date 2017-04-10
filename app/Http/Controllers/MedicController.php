<?php

namespace App\Http\Controllers;


use App\Repositories\AppointmentRepository;
use App\Repositories\MedicRepository;
use App\Repositories\OfficeRepository;
use App\Repositories\ScheduleRepository;
use App\Speciality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class MedicController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(MedicRepository $medicRepo, AppointmentRepository $appointmentRepo, ScheduleRepository $scheduleRepo, OfficeRepository $officeRepo)
    {
        $this->middleware('auth');
        $this->medicRepo = $medicRepo;
        $this->appointmentRepo = $appointmentRepo;
         $this->scheduleRepo = $scheduleRepo;
          $this->officeRepo = $officeRepo;
       

        View::share('specialities', Speciality::all());
    }


    /**
     * Mostrar vista de todas las consulta(citas) de un doctor
     */
    public function index()
    {
        if(!auth()->user()->active) return Redirect('/');

       $medics = [];
       $specialist = request('specialist');
       $general = request('general');
       
        return view('medics.index',compact('medics','specialist','general'));

    }
    /**
     * Mostrar vista de todas las consulta(citas) de un doctor
     */
    public function search()
    {
     
       //if(!auth()->user()->active) return Redirect('/');

       $medics = [];
       
       if(request()->all())
       {
            if(trim(request('q')) != '' || request('province') != '' || request('canton') != '' || request('district') != '' || request('lat') != '' || request('lon') != '' || request('speciality') != ''){
                $search['q'] = trim(request('q'));
                $search['speciality'] = request('speciality');
                $search['province'] = request('province');
                $search['canton'] = request('canton');
                $search['district'] = request('district');
                $search['lat'] = request('lat');
                $search['lon'] = request('lon');
                $search['general'] = request('general');
                $search['active'] = 1;
                $selectedSpeciality = $search['speciality'];
                
                
                $medics = $this->medicRepo->findAll($search);
                //$count = $medics->count();

                //flash('Se '. (($count > 1) ? "encontraron" : "encontró ") . $count.' Médico(s)','success');
                
                if(request('speciality'))
                {
                    $specialist = 1;
                    return view('medics.index',compact('medics','search','selectedSpeciality','specialist'));   
                }
                if(request('general'))
                {
                    $general = 1;
                    return view('medics.index',compact('medics','search','selectedSpeciality','general'));   
                }
                
               
                    
                

                 return view('medics.index',compact('medics','search','selectedSpeciality'));
            }
        }

        return view('medics.index',compact('medics','specialist'));

    }

    /**
     * Mostrar vista de todas las consulta(citas) de un doctor
     */
    public function schedule($medic_id, $office_id)
    {
        if(!auth()->user()->active) return redirect('/');

        $medic = $this->medicRepo->findbyId($medic_id);
         $office = $this->officeRepo->findbyId($office_id);
        
        if(!$medic->hasrole('medico')) return redirect('/');
        if(!$medic->verifyOffice($office_id)) return redirect('/');
        
        return view('medics.schedule',compact('medic','office'));
    }

    /**
     * Lista de todas las citas de un doctor sin paginar
     */
    public function getAppointments($id)
    {

        $appointments = $this->appointmentRepo->findAllByDoctorWithoutPagination($id, request()->all());
        
        return $appointments;
        
    }

     /**
     * Lista de todas las citas de un doctor sin paginar
     */
    public function getSchedules($id)
    {

        $schedules = $this->scheduleRepo->findAllByDoctorWithoutPagination($id, request()->all());

        return $schedules;
        
    }

   
}
