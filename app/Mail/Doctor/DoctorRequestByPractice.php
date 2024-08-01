<?php

namespace App\Mail\Doctor;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DoctorRequestByPractice extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $practiceRequest;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($practiceRequest)
    {
        $this->practiceRequest = $practiceRequest;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        $address = config('constants.iCarePro_SUPPORT_EMAIL');
        $name = config('constants.EMAIL_FROM_NAME');
        $subject = 'Doctor Request'. $this->practiceRequest->status;
        return $this->view('email.Doctor.doctor-request-by-practice', ['practiceRequest' => $this->practiceRequest])
            ->from($address, $name)
            ->replyTo($address, $name)
            ->subject($subject);
    }
}
