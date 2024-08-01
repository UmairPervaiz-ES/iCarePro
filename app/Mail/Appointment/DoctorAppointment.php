<?php

namespace App\Mail\Appointment;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DoctorAppointment extends Mailable
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
         $subject = 'Doctor Appointment';
         $doctorLogin = config('constants.EMAIL_DOCTOR_LOGIN');
         return $this->view('email.Appointment.doctor',['appointment'=>$this->appointment,'patient'=>$this->patient,'doctor'=>$this->doctor,'practice' => $this->practice , 'doctorLogin'=>$doctorLogin])
             ->from($address, $name)
             ->replyTo($address, $name)
             ->subject($subject);
            }
}
