<?php

namespace App\Mail\Practice;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PracticeDetailsReject extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $practice;

    public function __construct($practice)
    {
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
        $subject = 'Practice Request Rejected';
        $practiceRegistration = config('constants.EMAIL_PRACTICE_REQUEST_REGISTRATION');


        return $this->view('email.Practice.practice-details-reject',['practice'=>$this->practice,'practiceRegistration'=> $practiceRegistration])
        ->from($address, $name)
        ->replyTo($address, $name)
        ->subject($subject);
    }
}
