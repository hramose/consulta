<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Broadcast::routes();

        /*
         * Authenticate the user's personal channel...
         */
        Broadcast::channel('App.User.*', function ($user, $userId) {
            return (int) $user->id === (int) $userId;
        });
        Broadcast::channel('users.{id}.notifications', function ($user, $id) {
            return (int) $user->id === (int) $id;
        });
        Broadcast::channel('offices.{id}.notifications', function ($user, $id) {
            $office = $user->clinicsAssistants->first();

            return (int) $office->id === (int) $id;
        });
        Broadcast::channel('adminclinic.{id}.notifications', function ($user, $id) {
            return (int) $user->id === (int) $id;
        });
    }
}
