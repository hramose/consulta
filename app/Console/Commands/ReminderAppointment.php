<?php

namespace App\Console\Commands;

use App\Mail\ReminderAppointment;
use App\Reminder;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;


class ReminderAppointment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consulta:reminderAppointment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recordatorio de cita al paciente';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        $remiders = Reminder::with('appointment')->where('active', 1)->get();
        $countNotification = 0;

        foreach($remiders as $remider)
        {
           if($remider->active && $remider->appointment->date != '0000-00-00 00:00:00')
           {
               
               $timeArray = explode(':', $remider->reminder_time);


               $dtReminder = Carbon::parse($remider->appointment->start);
               //$dt = Carbon::parse($remider->appointment->start);
               //$dt->setTime($timeArray[0], $timeArray[1], 0);
               //if($remider->id == 6)
               // dd(Carbon::now()->diffInHours($dtReminder));
              
                if(Carbon::now()->diffInHours($dtReminder) <= $timeArray[0])
               // if(Carbon::now()->diffInMinutes($dtReminder) == 0)
                {
                    
                   
                    try {
                        
                        
                         \Mail::to([$remider->appointment->patient->email])->send(new ReminderAppointment($remider->appointment));

                        $remider->active = 0;
                        $remider->save();

                    }catch (Swift_RfcComplianceException $e)
                    {
                        Log::error($e->getMessage());
                    }

                    $countNotification++;
                    
                    Log::info(Carbon::now()->diffInMinutes($dtReminder));
                }
                

        
            }

        }
        Log::info($countNotification.' recordatorios de citas enviadas' );
        $this->info('Hecho, ' . $countNotification.' recordatorios de citas enviadas');

       
    }
}
