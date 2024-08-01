<?php

namespace App\Jobs\Doctor;

use App\Helper\Helper;
use App\Mail\Doctor\DoctorKYCVerification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class KYCVerification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public int $tries = 3;

    public $doctor,$kyc_verification_url;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($kyc_verification_url, $doctor)
    {
        $this->doctor = $doctor;
        $this->kyc_verification_url = $kyc_verification_url;
        $this->onQueue(config('constants.KYC_VERIFICATION'));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->doctor->primary_email)->send(new DoctorKYCVerification($this->kyc_verification_url, $this->doctor));
    }

    public function failed(\Exception $exception)
    {
        Helper::triggerNotification($exception);
    }
}
