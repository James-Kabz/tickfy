<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $customerDetails;
    public $callbackData;

    public function __construct($customerDetails, $callbackData)
    {
        $this->customerDetails = $customerDetails;
        $this->callbackData = $callbackData;
    }

    public function build()
    {
        return $this->subject('Payment Confirmation')
            ->view('emails.payment_confirmation')
            ->with([
                'customerDetails' => $this->customerDetails,
                'callbackData' => $this->callbackData,
            ]);
    }
}
