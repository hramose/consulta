<?php

namespace App\Http\Controllers\Api\Medic;


use App\Http\Controllers\Api\ApiController;
use App\Repositories\AppointmentRepository;
use App\Repositories\MedicRepository;
use App\Repositories\ScheduleRepository;
use App\Speciality;
use App\User;
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


    
}
