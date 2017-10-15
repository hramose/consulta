<?php

namespace App\Mail;

use App\Office;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewContact extends Mailable
{
    use Queueable, SerializesModels;

    public $dataMessage;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($dataMessage)
    {
        $this->dataMessage = $dataMessage;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Nueva consulta desde el formulario de soporte')->markdown('emails.newContact');
    }
}
