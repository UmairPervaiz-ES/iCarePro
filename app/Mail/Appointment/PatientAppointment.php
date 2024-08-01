<?php

namespace App\Mail\Appointment;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PatientAppointment extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $appointment, $doctor, $patient, $practice;

    public function __construct($appointment, $doctor, $patient, $practice)
    {
        $this->appointment = $appointment;
        $this->doctor = $doctor;
        $this->patient = $patient;
        $this->practice = $practice;
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
        $subject = 'Patient Appointment';
        $practiceLogin = config('constants.EMAIL_PATIENT_LOGIN');
        return $this->view('email.Appointment.patient', ['appointment' => $this->appointment, 'doctor' => $this->doctor, 'patient' => $this->patient, 'practice' => $this->practice,
         'practiceLogin'=>$practiceLogin])
            ->from($address, $name)
            ->replyTo($address, $name)
            ->subject($subject);
    }
}
