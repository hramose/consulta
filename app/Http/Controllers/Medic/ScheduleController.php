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
       
        $datefin1 = Carbon::parse(request('datefin1'));
        $datefin2 = Carbon::parse(request('datefin2'));
        $dateini1 = Carbon::parse(request('dateini1'));
        $dateini2 = Carbon::parse(request('dateini2'));
        $search['date1'] =  $datefin1;//Carbon::now()->startOfWeek();
        $search['date2'] = $datefin2; //Carbon::now()->endOfWeek();

        if(request('dateini1') == request('datefin1') || request('dateini2') == request('datefin2')){

            flash('Seleccione fechas de inicio y fin diferentes!','error'); 
            return back();
        }

         if($datefin1 <  $dateini1 || $datefin2 <  $dateini2){

            flash('La fecha de inicio no puede ser mayor que la fecha final. Verifique!','error'); 
            return back();
        }

        $schedules = $this->scheduleRepo->findAllByDoctorWithoutPagination(auth()->id(), $search);
        
        if($schedules->count()){

            flash('No se puede copiar por que ya hay un horario programado en esta semana, verifica!','error'); 
            return back();
        }


        $search['date1'] = $dateini1;//Carbon::now()->subWeek()->startOfWeek();
        $search['date2'] = $dateini2;//Carbon::now()->subWeek()->endOfWeek();
        $copiedSchedules = 0;
        $copySchedules = $this->scheduleRepo->findAllByDoctorWithoutPagination(auth()->id(), $search);  

        foreach ($copySchedules as $scheduleToCopy) {
            $dateBetween = Carbon::parse($scheduleToCopy->date);
            $dateStart = Carbon::parse($scheduleToCopy->start);
            $dateEnd = Carbon::parse($scheduleToCopy->end);
           
            while (!$dateBetween->between($datefin1, $datefin2) &&  ($dateBetween->month == $datefin1->month || $dateBetween->month == $datefin2->month)){
                  $dateBetween->addWeek();
                  $dateStart->addWeek();
                  $dateEnd->addWeek();
              
            }
           

            if($scheduleToCopy->date != $dateBetween->toDateTimeString() && ($dateBetween->month == $datefin1->month || $dateBetween->month == $datefin2->month))
            {
                Schedule::create([
                        'user_id' => $scheduleToCopy->user_id,
                        'office_id' => $scheduleToCopy->office_id,
                        'date' => $dateBetween->toDateTimeString(),
                        'start' => $dateStart->toDateString().'T'.$dateStart->toTimeString(),
                        'end' => $dateEnd->toDateString().'T'.$dateEnd->toTimeString(),
                        'allDay' => $scheduleToCopy->allDay,
                        'title' => $scheduleToCopy->title,
                        'backgroundColor' => $scheduleToCopy->backgroundColor,
                        'borderColor' => $scheduleToCopy->borderColor,
                        'status' => $scheduleToCopy->status
                    ]);

                $copiedSchedules++;
            }
            /*Schedule::create([
                    'user_id' => $scheduleToCopy->user_id,
                    'office_id' => $scheduleToCopy->office_id,
                    'date' => $dateBetween->toDateTimeString(),
                    'start' => Carbon::parse($scheduleToCopy->start)->addWeek()->toDateString().'T'.Carbon::parse($scheduleToCopy->start)->addWeek()->toTimeString(),
                    'end' => Carbon::parse($scheduleToCopy->end)->addWeek()->toDateString().'T'.Carbon::parse($scheduleToCopy->end)->addWeek()->toTimeString(),
                    'allDay' => $scheduleToCopy->allDay,
                    'title' => $scheduleToCopy->title,
                    'backgroundColor' => $scheduleToCopy->backgroundColor,
                    'borderColor' => $scheduleToCopy->borderColor,
                    'status' => $scheduleToCopy->status
                ]);*/
        }
        
         flash($copiedSchedules. ' Horario(s) copiado(s) con exito!','success'); 

        return back();
    }

    

}
