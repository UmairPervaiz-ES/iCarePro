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

class CancelAppointment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public int $tries = 3;

    public $email,$appointment;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $appointment)
    {
        $this->email = $email;
        $this->appointment = $appointment;
        $this->onQueue(config('constants.CancelAppointment'));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->email)->send(new \App\Mail\Doctor\CancelAppointment($this->appointment));
    }

    public function failed(\Exception $exception)
    {
        Helper::triggerNotification($exception);
    }
}
