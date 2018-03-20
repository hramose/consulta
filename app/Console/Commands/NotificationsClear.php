<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\AppNotification;
use Illuminate\Support\Carbon;

class NotificationsClear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consulta:clearNotifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpiar las notificaciones de las apps con mas de un mes de antiguedad';

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
        $notifications = AppNotification::where('created_at', '>=', Carbon::now()->subMonth()->toDateTimeString())->get();
        foreach ($notifications as $notification) {
            $notification->delete();
        }
        //dd($notifications);
    }
}
