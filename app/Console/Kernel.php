<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\NotificationOfficeLocation::class,
        \App\Console\Commands\ReminderAppointment::class,
        \App\Console\Commands\SendPolls::class,
         \App\Console\Commands\MonthlyCharge::class,
          \App\Console\Commands\SubscriptionCharge::class,
          \App\Console\Commands\NotificationsClear::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Run every 5 minutes
        //$schedule->command('queue:work')->everyFiveMinutes();
        //$schedule->command('queue:work')->cron('* * * * * *');

        $schedule->command(\App\Console\Commands\NotificationOfficeLocation::class)
                 ->everyThirtyMinutes(); //se hace asi por que este no es necesario enviar email

        $schedule->command(\App\Console\Commands\SubscriptionCharge::class)
                 ->dailyAt('00:05'); //se hace asi por que este no es necesario enviar email

        $schedule->command(\App\Console\Commands\MonthlyCharge::class)
                 ->monthlyOn(1,'00:05'); //se hace asi por que este no es necesario enviar email

        //dailyAt
        /*$schedule->command(\App\Console\Commands\ReminderAppointment::class) //hay q verificar si en el nuevo servidor si funciona asi
                 ->everyThirtyMinutes();*/

        $schedule->call(function () { // lo hacemos de esta forma porque no esta enviando los email
            //url contra la que atacamos
            $ch = curl_init(env('APP_URL') . '/appointments/reminder');
            //a true, obtendremos una respuesta de la url, en otro caso,
            //true si es correcto, false si no lo es
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //establecemos el verbo http que queremos utilizar para la petición
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            //enviamos el array data
            //curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
            //obtenemos la respuesta
            $response = curl_exec($ch);
            // Se cierra el recurso CURL y se liberan los recursos del sistema
            curl_close($ch);
            Log::info('se llamó appointment/reminder: ' . $response);
        })->everyThirtyMinutes();

        /* $schedule->command(\App\Console\Commands\SendPolls::class) //hay q verificar si en el nuevo servidor si funciona asi
                  ->daily();*/

        $schedule->call(function () { // lo hacemos de esta forma porque no esta enviando los email
            //url contra la que atacamos
            $ch = curl_init(env('APP_URL') . '/polls/send');
            //a true, obtendremos una respuesta de la url, en otro caso,
            //true si es correcto, false si no lo es
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //establecemos el verbo http que queremos utilizar para la petición
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            //enviamos el array data
            //curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
            //obtenemos la respuesta
            $response = curl_exec($ch);
            // Se cierra el recurso CURL y se liberan los recursos del sistema
            curl_close($ch);
        })->daily();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
