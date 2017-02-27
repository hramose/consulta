<?php

namespace App\Console\Commands;

use App\Office;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class NotificationOfficeLocation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consulta:notificationOfficeLocation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notificacion de actualizacion de ubicacion de la clinica o consultorio';

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
        $offices = Office::all();
        $countNotification = 0;

        foreach($offices as $office)
        {
           if($office->notification_date != '0000-00-00 00:00:00')
           {
               
               //$timeArray = explode(':', $task->notification_reminder_time);

               
               $dtOffice = Carbon::createFromFormat('Y-m-d H:i:s', $office->notification_date);
               //$dtOffice->setTime($timeArray[0], $timeArray[1], 0);
             // dd(Carbon::now()->diffInMinutes($dtOffice));
              
                if(Carbon::now()->diffInMinutes($dtOffice) == 0)
                {
                    
                    $office->notification = 1;
                    $office->save();
                    /*try {
                        
                        $this->mailer->notificationTasks(['office'=> $office]);
                        
                    }catch (Swift_RfcComplianceException $e)
                    {
                        Log::error($e->getMessage());
                    }*/

                    $countNotification++;
                    
                    Log::info(Carbon::now()->diffInMinutes($dtOffice));
                }
                

        
            }

        }
        Log::info($countNotification.' notificaciones de actualizacion de clinicas enviadas' );
        $this->info('Hecho, ' . $countNotification.' notificaciones de actualizacion de clinicas enviadas');
       
    }
}
