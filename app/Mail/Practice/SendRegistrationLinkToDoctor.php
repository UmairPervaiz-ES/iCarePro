<?php

namespace App\Mail\Practice;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendRegistrationLinkToDoctor extends Mailable
{
    use Queueable, SerializesModels;

    protected $doctor, $password, $practice;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($doctor, $password, $practice)
    {
        $this->doctor = $doctor;
        $this->password = $password;
        $this->practice = $practice;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $doctor = $this->doctor;
        $password = $this->password;
        $practice = $this->practice;
        $doctorLogin = config('constants.EMAIL_DOCTOR_LOGIN');


        $address = config('constants.iCarePro_SUPPORT_EMAIL');
        $name = config('constants.EMAIL_FROM_NAME');
        $subject = 'Doctor Registration';
        return $this->view('email.Practice.send-registration-link-to-doctor', compact('doctor', 'password','practice','doctorLogin'))
            ->from($address, $name)
            ->replyTo($address, $name)
            ->subject($subject);
    }
}
