<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\User;
use App\RequestOffice;
use Illuminate\Support\Facades\View;

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
                if ($request->has('sql-debug')) {
                    $queries = \DB::getQueryLog();
                    dd($queries);
                }
            });
        }

        view()->composer('layouts.app', function ($view) {
            $appointments = \App\Appointment::with('user', 'patient')->where('user_id', auth()->id())->where('status', 0)->where('patient_id', '<>', 0)->where('viewed', 0)->limit(10);

            $newAppointments = $appointments->get();

            $view->with('newAppointments', $newAppointments);
        });

        view()->composer('layouts.app-assistant', function ($view) {
            $office = auth()->user()->clinicsAssistants->first();

            $appointments = \App\Appointment::with('user', 'patient')->where('office_id', $office->id)->where('status', 0)->where('patient_id', '<>', 0)->where('viewed_assistant', 0)->limit(10);

            $newAppointments = $appointments->get();

            $view->with('newAppointments', $newAppointments);
        });

        view()->composer('layouts.partials.header-clinic', function ($view) {
            $office_id = auth()->user()->offices->first()->id;

            $medicsVerified = \DB::table('verified_offices')
                    ->where('office_id', $office_id)

                    ->pluck('user_id');

            $medicsNoVerified = User::whereNotIn('users.id', $medicsVerified)->whereHas('roles', function ($q) {
                $q->where('name', 'medico');
            })->whereHas('offices', function ($q) use ($office_id) {
                $q->where('offices.id', $office_id);
            })->limit(10)->get();

            //\Log::info('no v:' . $medicsNoVerified);
            // \Log::info('ver:' . $medicsVerified);

            $view->with('newMedicsRequest', $medicsNoVerified);
        });

        view()->composer('layouts.partials.home-boxes-admin', function ($view) {
            $medics = User::whereHas('roles', function ($q) {
                $q->where('name', 'medico');
            })->where('active', 0)->count();

            $admins = User::whereHas('roles', function ($q) {
                $q->where('name', 'clinica');
            })->where('active', 0)->count();

            $requests = RequestOffice::where('status', 0)->count();

            $view->with(compact('medics', 'admins', 'requests'));
        });

        view()->composer('layouts.partials.header', function ($view) {
            $clinicsUser = auth()->user()->offices;

            $view->with(compact('clinicsUser'));
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
