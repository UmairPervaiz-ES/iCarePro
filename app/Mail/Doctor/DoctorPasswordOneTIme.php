<?php

namespace App\Mail\Doctor;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DoctorPasswordOneTIme extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $doctor, $password;

    public function __construct($doctor, $password)
    {
        $this->doctor = $doctor;
        $this->password = $password;
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
        $subject = 'Doctor Request Accepted';
        $doctorLogin = config('constants.EMAIL_DOCTOR_LOGIN');
        return $this->view('email.Doctor.doctor-password-onetime', ['doctor' => $this->doctor, 'password' => $this->password,'doctorLogin'=>$doctorLogin])
            ->from($address, $name)
            ->replyTo($address, $name)
            ->subject($subject);
    }
}
