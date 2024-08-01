<?php

namespace App\Mail\Practice;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendRegistrationMessageToPatient extends Mailable
{
    use Queueable, SerializesModels;

    protected $patient, $practice_name;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($patient, $practice_name)
    {
        $this->patient = $patient;
        $this->practice_name = $practice_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $patientLogin = config('constants.EMAIL_PATIENT_LOGIN');

        $patient = $this->patient;
        $practice_name = $this->practice_name;
        $address = config('constants.iCarePro_SUPPORT_EMAIL');
        $name = config('constants.EMAIL_FROM_NAME');
        $subject = 'Patient Registration Confirmation';


        return $this->view('email.Practice.send-registration-verification-to-patient', compact('patient', 'practice_name','patientLogin'))
            ->from($address, $name)
            ->replyTo($address, $name)
            ->subject($subject);
    }
}
