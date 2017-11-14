<?php

namespace App\Http\Controllers\Api\Medic;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Repositories\ScheduleRepository;
use App\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;


class ScheduleController extends ApiController
{
    
    function __construct(ScheduleRepository $scheduleRepo)
    {
    	
        $this->middleware('auth');
    	$this->scheduleRepo = $scheduleRepo;

    }



    /**
     * Guardar consulta(cita)
     */
    public function store()
    {

        $schedule = $this->scheduleRepo->store(request()->all());
        
        if(!$schedule) return '';

        $schedule['office'] = $schedule->office;
        $schedule['user'] = $schedule->user;

        return $schedule;

    }
     /**
     * Guardar consulta(cita)
     */
    public function storeAll()
    {
        $dataArray = request()->all();

        foreach ($dataArray as $schedule) {
            $sc = $this->scheduleRepo->store($schedule);
        }

        return $sc;

    }


    /**
     * Actualizar consulta(cita)
     */
    public function update($id)
    {
        
        $schedule = $this->scheduleRepo->update($id, request()->all());
        
        if($schedule){
            $schedule['office'] = $schedule->office;
            $schedule['user'] = $schedule->user;
        }
        
        return $schedule;

    }

   
     /**
     * Eliminar consulta(cita) ajax desde calendar
     */
    public function delete($id)
    {

        $schedule = $this->scheduleRepo->delete($id);
        
         //if($schedule !== true)  return $schedule; //no se elimino correctamente
         if($schedule === true) return $this->respondDeleted('Horario Eliminado Correctamente');
     
 
        return $this->respondforbidden('No se ha podido eliminar el horario','error');

    
    }

    /**
     * Lista de todas las citas de un doctor sin paginar
     */
    public function getSchedules()
    {
        $search = request()->all();
        $search['date1'] = isset($search['date1']) ? Carbon::parse($search['date1']) : '';
        $search['date2'] =  isset($search['date2']) ? Carbon::parse($search['date2']) : '';

        $schedules = $this->scheduleRepo->findAllByDoctorWithoutPagination(auth()->id(), $search);

        return $schedules;
        
    }
    

    

}
