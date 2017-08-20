<?php

namespace App\Http\Controllers\Api;


use App\Repositories\AppointmentRepository;
use App\Repositories\MedicRepository;
use App\Speciality;
use App\User;
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
                
                
                $medics = $this->medicRepo->findAll($search);
               
               
                    
                

                 return $medics;
            }
        }

         return $medics;

    }

     /**
     * Display the specified resource.
     *
     * @param $id
     * @return Response
     * @internal param int $id
     */
     public function show($id)
     {
         $medic = User::with('offices','specialities')->find($id);
 
         if(! $medic)
         {
             return $this->respondNotFound('Medico does not exist');
 
         }
         return $this->respond([
            'data' => $medic//$this->productTransformer->transform($product)
         ]);
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
    
    /**
     * Lista de todas las citas de un doctor sin paginar
     */
    public function getAppointments($id)
    {
        $search = request()->all();
        $search['date1'] = isset($search['date1']) ? Carbon::parse($search['date1']) : '';
        $search['date2'] =  isset($search['date2']) ? Carbon::parse($search['date2']) : '';

        $appointments = $this->appointmentRepo->findAllByDoctorWithoutPagination($id, $search);
        
        return $appointments;
        
    }

   function getSpecialities () {
        return Speciality::all();
    }



    
}
