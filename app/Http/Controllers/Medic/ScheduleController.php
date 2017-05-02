<?php

namespace App\Http\Controllers\Medic;

use App\Http\Controllers\Controller;
use App\Repositories\ScheduleRepository;
use App\Schedule;
use Carbon\Carbon;
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
    public function copySchedule($week)
    {
        $search['date1'] =  Carbon::now()->startOfWeek();
        $search['date2'] = Carbon::now()->endOfWeek();

        $schedules = $this->scheduleRepo->findAllByDoctorWithoutPagination(auth()->id(), $search);
        
        if($schedules->count()){

            flash('No se puede copiar por que ya hay un horario programado en esta semana, verifica!','error'); 
            return back();
        }

        //dd(Carbon::now()->subWeek()->startOfWeek() .'----'.Carbon::now()->subWeek()->endOfWeek());

        $search['date1'] = Carbon::now()->subWeek()->startOfWeek();
        $search['date2'] = Carbon::now()->subWeek()->endOfWeek();

        $copySchedules = $this->scheduleRepo->findAllByDoctorWithoutPagination(auth()->id(), $search);  

        foreach ($copySchedules as $scheduleToCopy) {

            Schedule::create([
                    'user_id' => $scheduleToCopy->user_id,
                    'office_id' => $scheduleToCopy->office_id,
                    'date' => Carbon::parse($scheduleToCopy->date)->addWeek()->toDateTimeString(),
                    'start' => Carbon::parse($scheduleToCopy->start)->addWeek()->toDateString().'T'.Carbon::parse($scheduleToCopy->start)->addWeek()->toTimeString(),
                    'end' => Carbon::parse($scheduleToCopy->end)->addWeek()->toDateString().'T'.Carbon::parse($scheduleToCopy->end)->addWeek()->toTimeString(),
                    'allDay' => $scheduleToCopy->allDay,
                    'title' => $scheduleToCopy->title,
                    'backgroundColor' => $scheduleToCopy->backgroundColor,
                    'borderColor' => $scheduleToCopy->borderColor,
                    'status' => $scheduleToCopy->status
                ]);
        }
        
         flash('Horario copiado con exito!','success'); 
        return back();
    }

    

}
