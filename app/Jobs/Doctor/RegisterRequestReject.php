<?php

namespace App\Jobs\Doctor;

use App\Helper\Helper;
use App\Mail\Doctor\DoctorRequestReject;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;


class RegisterRequestReject implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

     /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $doctor;

    public function __construct($doctor)
    {
        $this->doctor = $doctor;
        $this->onQueue(config('constants.DOCTOR_REQUEST_REJECT'));



    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->doctor['primary_email'])->send(new DoctorRequestReject($this->doctor));

    }

    public function failed(\Exception $exception)
    {
        Helper::triggerNotification($exception);
    }
}
