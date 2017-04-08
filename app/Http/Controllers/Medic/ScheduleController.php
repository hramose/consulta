<?php

namespace App\Http\Controllers\Medic;

use App\Http\Controllers\Controller;
use App\Repositories\ScheduleRepository;
use Illuminate\Http\Request;


class ScheduleController extends Controller
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
     * Eliminar consulta(cita)
     */
    public function destroy($id)
    {

        $schedule = $this->scheduleRepo->delete($id);

        ($schedule === true) ? flash('Horario eliminado correctamente!','success') : flash('No se puede eliminar el horario ya que tiene citas agendas','error');

        return back();

    }
     /**
     * Eliminar consulta(cita) ajax desde calendar
     */
    public function delete($id)
    {

        $schedule = $this->scheduleRepo->delete($id);
        
        if($schedule !== true)  return $schedule; //no se elimino correctamente

        return '';

    }

    /**
     * Lista de todas las citas de un doctor sin paginar
     */
    public function getSchedules()
    {

        $schedules = $this->scheduleRepo->findAllByDoctorWithoutPagination(auth()->id());

        return $schedules;
        
    }

    

}
