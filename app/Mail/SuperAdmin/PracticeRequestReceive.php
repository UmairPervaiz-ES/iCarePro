<?php

namespace App\Mail\SuperAdmin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PracticeRequestReceive extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $superAdmin, $initialPractice;

    public function __construct($superAdmin,$initialPractice)
    {
        $this->superAdmin = $superAdmin;
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
        $subject = 'Practice Registration Request Received';
        $superAdminLogin = config('constants.EMAIL_SUPER_ADMIN_LOGIN');


        return $this->view('email.SuperAdmin.Practice-request-receive',['superAdmin'=>$this->superAdmin,
        'initialPractice' => $this->initialPractice,'superAdminLogin'=> $superAdminLogin
        ])
        ->from($address, $name)
        ->replyTo($address, $name)
        ->subject($subject);
    }
}
