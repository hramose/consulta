<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (env('APP_ENV') === 'local') {
            \DB::connection()->enableQueryLog();
        }
        if (env('APP_ENV') === 'local') {
            \Event::listen('kernel.handled', function ($request, $response) {
                if ( $request->has('sql-debug') ) {
                    $queries = \DB::getQueryLog();
                    dd($queries);
                }
            });
        }

        view()->composer('layouts.app', function ($view)
        {
             $appointments = \App\Appointment::where('user_id', auth()->id())->where('status', 0)->where('patient_id','<>',0)->whereDate('date', \Carbon\Carbon::today()->toDateTimeString());
               
            

            $newAppointments = $appointments->count();

           
            $view->with('newAppointments', $newAppointments);
        });

         view()->composer('layouts.app-assistant', function ($view)
        {
               $office = auth()->user()->clinicsAssistants->first();

            
                $appointments = \App\Appointment::where('office_id', $office->id)->where('status', 0)->where('patient_id','<>',0)->whereDate('date', \Carbon\Carbon::today()->toDateTimeString());
               
            

            $newAppointments = $appointments->count();

           
            $view->with('newAppointments', $newAppointments);
        });
        /* \DB::listen(function ($query) {
            var_dump($query->sql);
            // $query->bindings
            // $query->time
        });*/
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
