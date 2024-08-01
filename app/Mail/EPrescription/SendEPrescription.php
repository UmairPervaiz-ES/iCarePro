<?php

namespace App\Mail\EPrescription;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEPrescription extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $file;

    public function __construct($details)
    {  
        $this->details = $details;
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $address = config('constants.iCarePro_SUPPORT_EMAIL');
        $name = config('constants.EMAIL_FROM_NAME');
        $subject = 'ePrescription';

        return $this->view("email.eprescription", ['details' => $this->details])->attach($this->details['file'])
        ->from($address, $name)
        ->replyTo($address, $name)
        ->subject($subject);
    }
}
