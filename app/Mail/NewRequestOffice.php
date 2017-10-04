<?php

namespace App\Mail;

use App\RequestOffice;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewRequestOffice extends Mailable
{
    use Queueable, SerializesModels;

    public $requestOffice;
   
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(RequestOffice $requestOffice)
    {
        $this->requestOffice = $requestOffice;
      
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Nueva solicitud de integracion de clínica ')->markdown('emails.newRequestOffice');
    }
}
