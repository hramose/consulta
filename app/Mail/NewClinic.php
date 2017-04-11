<?php

namespace App\Mail;

use App\Office;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewClinic extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $clinic;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Office $clinic)
    {
        $this->user = $user;
        $this->clinic = $clinic;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Nuevo Administrador de clínica Registrado')->markdown('emails.newClinic');
    }
}
