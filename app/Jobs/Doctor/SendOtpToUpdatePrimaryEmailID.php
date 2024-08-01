<?php

namespace App\Jobs\Doctor;

use App\Helper\Helper;
use App\Mail\Doctor\UpdatePrimaryEmailID;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOtpToUpdatePrimaryEmailID implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public int $tries = 3;

    public $update_primary_email,$doctor,$otp;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($update_primary_email, $otp, $doctor)
    {
        $this->update_primary_email = $update_primary_email;
        $this->doctor = $doctor;
        $this->otp = $otp;
        $this->onQueue(config('constants.SEND_OTP_TO_UPDATE_PRIMARY_EMAIL_ID'));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->update_primary_email)->send(new UpdatePrimaryEmailID($this->otp, $this->doctor));
    }

    public function failed(\Exception $exception)
    {
        Helper::triggerNotification($exception);
    }
}
