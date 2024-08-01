<?php

namespace App\Mail\Practice;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PracticeRegister extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $initialPractice;

    public function __construct($initialPractice)
    {
        $this->initialPractice = $initialPractice;
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
        $subject = 'Registration Request Approved';
        $practiceRegister = config('constants.EMAIL_PRACTICE_REGISTRATION');



        return $this->view('email.Practice.practice-register',['practiceRegister' => $practiceRegister , 'initialPractice'=>$this->initialPractice])
        ->from($address, $name)
        ->replyTo($address, $name)
        ->subject($subject);
    }
}
