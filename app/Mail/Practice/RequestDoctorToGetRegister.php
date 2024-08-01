<?php

namespace App\Mail\Practice;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestDoctorToGetRegister extends Mailable
{
    use Queueable, SerializesModels;

    protected $doctorPractice;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($doctorPractice)
    {
        $this->doctorPractice = $doctorPractice;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        $doctorRequestRoute = config('constants.DOCTOR_REQUEST_ROUTE');


        $address = $this->doctorPractice->practice->email;
        $name = $this->doctorPractice->practice->initialPractice->first_name . ' ' . $this->doctorPractice->practice->initialPractice->middle_name . ' ' . $this->doctorPractice->practice->initialPractice->last_name;
        $subject = 'Request for registration as doctor';
        return $this->view('email.Practice.request-doctor-to-get-register',['doctorPractice'=> $this->doctorPractice, 'doctorRequestRoute'=> $doctorRequestRoute])
            ->from($address, $name)
            ->replyTo($address, $name)
            ->subject($subject);
    }
}
