<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\User;

class MedicRequest implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $medic;
    public $user_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $medic, $administratorsClinic)
    {
        $this->user_id = $administratorsClinic->first()->id;
        $this->medic = $medic->toJson();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        //dd($this->user_id);

        return new PrivateChannel('adminclinic.' . $this->user_id . '.notifications');
    }
}
