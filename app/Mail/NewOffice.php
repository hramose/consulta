<?php

namespace App\Mail;

use App\Office;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewOffice extends Mailable
{
    use Queueable, SerializesModels;

    public $office;
    public $medic;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Office $office, User $medic)
    {
        $this->office = $office;
        $this->medic = $medic;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Nueva clínica creada o asignada a un médico')->markdown('emails.newOffice');
    }
}
