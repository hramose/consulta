<?php

namespace App\Mail;

use App\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\RequestOffice;

class NewClinicIntegrated extends Mailable
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
        return $this->subject('Clínica integrada')->markdown('emails.newClinicIntegrated');
    }
}
