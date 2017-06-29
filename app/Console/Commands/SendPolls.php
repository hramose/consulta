<?php

namespace App\Console\Commands;

use App\Appointment;
use App\Mail\ReminderAppointments;
use App\Mail\SendPoll;
use App\Reminder;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;


class SendPolls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consulta:sendPolls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envio de la encuesta para feedback al medico o clinica';

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
        
        $appointments = Appointment::where('status', 1)->get();
        $countNotification = 0;

        foreach($appointments as $appointment)
        {
           if($appointment->date != '0000-00-00 00:00:00')
           {
                $patient_id = $appointment->patient_id;
                $medic_id = $appointment->user_id;
                $appointment_date = Carbon::parse($appointment->date);

                $users = User::whereHas('roles', function ($query) {
                        $query->where('name',  'paciente');
                    });

                $user = $users->whereHas('patients', function ($query) use ($patient_id){
                        $query->where('patients.id',  $patient_id);
                    })->first();

                //dd($user); 

               
                if($user && Carbon::now()->diffInDays($appointment_date) == 7)
                {
                    
                   
                    try {
                        
                        
                        \Mail::to([$user->email])->send(new SendPoll($medic_id));

                    Log::info(url('/medics/'.$medic_id.'/polls'));

                    }catch (\Swift_TransportException $e)
                    {
                        Log::error($e->getMessage());
                    }

                    $countNotification++;
                    
                    Log::info(Carbon::now()->diffInDays($appointment_date));
                }
               

        
            }

        }
        Log::info($countNotification.' encuestas de citas enviadas' );
        $this->info('Hecho, ' . $countNotification.' encuestas de citas enviadas');

       
    }
}
