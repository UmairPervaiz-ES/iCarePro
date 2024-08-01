<?php

namespace App\Mail\Doctor;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DoctorRequestReject extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $doctor;

    public function __construct($doctor)
    {
        $this->doctor = $doctor;
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
        $subject = 'Doctor Request Rejected';
        $parcticeLogin = config('constants.EMAIL_PRACTICE_LOGIN');

        return $this->view('email.Doctor.doctor-reject-request', ['doctor' => $this->doctor,
        'parcticeLogin'=> $parcticeLogin])
            ->from($address, $name)
            ->replyTo($address, $name)
            ->subject($subject);
    }
}
