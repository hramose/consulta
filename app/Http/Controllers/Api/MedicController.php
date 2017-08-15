<?php

namespace App\Http\Controllers\Api;


use App\Repositories\AppointmentRepository;
use App\Repositories\MedicRepository;
use App\Speciality;
use App\Repositories\ScheduleRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;


class MedicController extends ApiController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(MedicRepository $medicRepo, AppointmentRepository $appointmentRepo, ScheduleRepository $scheduleRepo)
    {
       
        $this->medicRepo = $medicRepo;
        $this->appointmentRepo = $appointmentRepo;
        $this->scheduleRepo = $scheduleRepo;

        
    }

     /**
     * Mostrar vista de todas las consulta(citas) de un doctor
     */
    public function search()
    {
     
       

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
                
                
                $medics = $this->medicRepo->apiFindAll($search);
               
               
                    
                

                 return $medics;
            }
        }

         return $medics;

    }
    /*
     * Lista de todas las citas de un doctor sin paginar
     */
    public function getSchedules($id)
    {
        $search = request()->all();
        $search['date1'] = isset($search['date1']) ? Carbon::parse($search['date1']) : '';
        $search['date2'] =  isset($search['date2']) ? Carbon::parse($search['date2']) : '';


        $schedules = $this->scheduleRepo->findAllByDoctorWithoutPagination($id, $search);

        return $schedules;
        
    }

   function getSpecialities () {
        return Speciality::all();
    }



    
}
