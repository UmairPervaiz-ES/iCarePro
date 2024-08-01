<?php

namespace App\Mail\Doctor;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DoctorKYCVerification extends Mailable
{
    use Queueable, SerializesModels;

    protected $url_verification , $doctor;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($url_verification,$doctor)
    {
        $this->url_verification = $url_verification;
        $this->doctor = $doctor;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        $verification_url = $this->url_verification;
        $doctor = $this->doctor ;

        $address = config('constants.iCarePro_SUPPORT_EMAIL');
        $name = config('constants.EMAIL_FROM_NAME');
        $subject = 'KYC Verification';
        return $this->subject('KYC verification')
            ->view('email.Doctor.kyc_verification', compact('verification_url','doctor'))
            ->from($address, $name)
            ->replyTo($address, $name)
            ->subject($subject);
    }
}
