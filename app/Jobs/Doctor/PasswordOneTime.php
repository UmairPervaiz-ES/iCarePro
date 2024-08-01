<?php

namespace App\Jobs\Doctor;

use App\Helper\Helper;
use App\Mail\Doctor\DoctorPasswordOneTIme;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;


class PasswordOneTime implements ShouldQueue
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

    public $doctor, $password;

    public function __construct($doctor, $password)
    {
        $this->doctor = $doctor;
        $this->password = $password;
        $this->onQueue(config('constants.DOCTOR_REQUEST_ACCEPT'));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->doctor['primary_email'])->send(new DoctorPasswordOneTIme($this->doctor, $this->password));
    }

    public function failed(\Exception $exception)
    {
        Helper::triggerNotification($exception);
    }
}
