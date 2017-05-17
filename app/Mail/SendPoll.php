<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendPoll extends Mailable
{
    use Queueable, SerializesModels;

    public $medic_id;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($medic_id)
    {
        $this->medic_id = $medic_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Encuesta de satisfacción del servicio brindado')->markdown('emails.sendPoll');
    }
}
