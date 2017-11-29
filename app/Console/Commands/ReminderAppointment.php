<?php

namespace App\Console\Commands;
use Edujugon\PushNotification\PushNotification;
use App\Mail\ReminderAppointments;
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
            //    if($remider->id == 9)
            //     $this->info('diff horas, ' . Carbon::now()->diffInHours($dtReminder) . '-- timearray '. $timeArray[0]);

                if(Carbon::now()->diffInHours($dtReminder) <= $timeArray[0])
               // if(Carbon::now()->diffInMinutes($dtReminder) == 0)
                {
                    $usersToPush = [];

                    $users_patient = $remider->appointment->patient->user()->whereHas('roles', function ($query){
                        $query->where('name',  'paciente');
                    })->get();
                   
                    foreach($users_patient as $user){
                        if($user->push_token)
                            $usersToPush[] = $user->push_token;
                    }
                    /*$remider->appointment->patient->users->each(function ($item, $key) {
                        if($item->push_token)
                            $usersToPush[] = $item->push_token;
                    });*/
                    
                    if( count($usersToPush) ){
                        $push = new PushNotification('fcm');
                        $response = $push->setMessage([
                            'notification' => [
                                    'title'=>'Notificación',
                                    'body'=>'Esto es un recordatorio que tienes un cita el '.  Carbon::parse($remider->appointment->start)->toDateTimeString(),
                                    'sound' => 'default'
                                    ]
                            
                            ])
                            ->setDevicesToken($usersToPush)
                            ->send()
                            ->getFeedback();
                            
                            Log::info('Mensaje Push code: '.$response->success);
                   }

                   if ($remider->appointment->patient && $remider->appointment->patient->email) { //si el paciente tiene correo lo envia
                       try {
                           \Mail::to([$remider->appointment->patient->email])->send(new ReminderAppointments($remider->appointment));

                           $remider->active = 0;
                           $remider->save();
                       } catch (\Swift_TransportException $e) {  //Swift_RfcComplianceException
                           Log::error($e->getMessage());
                       }
                   }

                    $countNotification++;
                    
                    Log::info('diff in hours '.Carbon::now()->diffInHours($dtReminder));
                }
                

        
            }

        }
        Log::info($countNotification.' recordatorios de citas enviadas' );
        $this->info('Hecho, ' . $countNotification.' recordatorios de citas enviadas');

       
    }
}
