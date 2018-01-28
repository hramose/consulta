<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentSubscriptionConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $plan;
    public $purchaseOperationNumber;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($plan, $purchaseOperationNumber)
    {
        $this->plan = $plan;
        $this->purchaseOperationNumber = $purchaseOperationNumber;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Confirmacion de pago')->markdown('emails.paymentSubscriptionConfirmation');
    }
}
