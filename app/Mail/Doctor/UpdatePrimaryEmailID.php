<?php

namespace App\Mail\Doctor;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UpdatePrimaryEmailID extends Mailable
{
    use Queueable, SerializesModels;

    protected $otp ,$doctor;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($otp ,$doctor)
    {
        $this->otp = $otp;
        $this->doctor =  $doctor;
    
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $otp = $this->otp;
        $doctor = $this->doctor;
        $address = config('constants.iCarePro_SUPPORT_EMAIL');
        $name = config('constants.EMAIL_FROM_NAME');
        $subject = 'Doctor Email Verify';
        return $this->view('email.Doctor.update_primary_email', compact('otp','doctor'))
            ->from($address, $name)
            ->replyTo($address, $name)
            ->subject($subject);
    }
}
