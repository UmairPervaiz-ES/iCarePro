<?php

namespace App\Mail\Practice;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PracticePasswordOneTime extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $practice ,$password;

    public function __construct($practice,$password)
    {    
        $this->practice = $practice;
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
        $subject = 'Practice Registration Approved';
        $practiceLogin = config('constants.EMAIL_PRACTICE_LOGIN');


        return $this->view('email.Practice.practice-password-onetime',['practice'=>$this->practice,'password'=>$this->password,'practiceLogin'=>$practiceLogin])
        ->from($address, $name)
        ->replyTo($address, $name)
        ->subject($subject);
    }
}
