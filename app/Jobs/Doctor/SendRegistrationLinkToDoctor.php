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

class SendRegistrationLinkToDoctor implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public int $tries = 3;

    public $doctor, $primary_email, $password, $practice;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($doctor, $password, $practice)
    {
        $this->doctor = $doctor;
        $this->practice = $practice;
        $this->primary_email = $doctor->primary_email;
        $this->password = $password;
        $this->onQueue(config('constants.SEND_REGISTRATION_LINK_TO_DOCTOR'));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->primary_email)->send(new \App\Mail\Practice\SendRegistrationLinkToDoctor($this->doctor, $this->password, $this->practice));
    }

    public function failed(\Exception $exception)
    {
        Helper::triggerNotification($exception);
    }
}
