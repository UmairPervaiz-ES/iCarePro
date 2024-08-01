<?php

namespace App\Mail\Staff;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Credentials extends Mailable
{
    use Queueable, SerializesModels;
    protected $password, $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($password,$user)
    {
        $this->password = $password;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        $password = $this->password;
        $user = $this->user ;
        $staffLogin = config('constants.EMAIL_STAFF_LOGIN');
        $address = config('constants.iCarePro_SUPPORT_EMAIL');
        $name = config('constants.EMAIL_FROM_NAME');
        $subject = 'Staff Request Accepted';
        return $this->view('email.Staff.credentials', compact('password','user', 'staffLogin'))
            ->from($address, $name)
            ->replyTo($address, $name)
            ->subject($subject);
    }
}
