<?php

namespace App\Jobs\Appointment;

use App\Events\Appointment\StatusUpdated;
use App\Helper\Helper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AppointmentStatusChanged implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public int $tries = 3;

    public $appointment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($appointment)
    {
        $this->appointment = $appointment;
        $this->onQueue(config('constants.APPOINTMENT_STATUS_UPDATE_NOTIFICATION'));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        event(new StatusUpdated($this->appointment));
    }

    public function failed(\Exception $exception)
    {
        Helper::triggerNotification($exception);
    }
}
