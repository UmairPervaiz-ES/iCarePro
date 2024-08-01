<?php

namespace App\Jobs\Doctor;

use App\Helper\Helper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class DoctorRequestByPractice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public int $tries = 3;

    public $practiceRequest;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($practiceRequest)
    {
        $this->practiceRequest = $practiceRequest;
        $this->onQueue(config('constants.DOCTOR_REQUEST_BY_PRACTICE'));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        Mail::to($this->practiceRequest->practice->email)->send(new \App\Mail\Doctor\DoctorRequestByPractice($this->practiceRequest));
    }

    public function failed(\Exception $exception)
    {
        Helper::triggerNotification($exception);
    }
}
