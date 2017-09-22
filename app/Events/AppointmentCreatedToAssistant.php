<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Appointment;

class AppointmentCreatedToAssistant implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $appointment;
    public $office_id;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Appointment $appointment)
    {
        $this->office_id =  $appointment->office->id;
        $this->appointment = $appointment->toJson();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('offices.'.$this->office_id.'.notifications');
    }
}
