<?php

namespace App\Jobs\Doctor;

use App\Events\Doctor\AppointmentCancelled;
use App\Helper\Helper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AppointmentCancelledNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public int $tries = 3;

    protected $appointment, $notification, $unread_notifications_count, $total_notifications;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($appointment, $notification, $unread_notifications_count, $total_notifications)
    {
        $this->appointment = $appointment;
        $this->notification = $notification;
        $this->unread_notifications_count = $unread_notifications_count;
        $this->total_notifications = $total_notifications;
        $this->onQueue(config('constants.APPOINTMENT_CANCELLED_DOCTOR_NOTIFICATION'));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        event(new AppointmentCancelled($this->appointment, $this->notification, $this->unread_notifications_count, $this->total_notifications));
    }

    public function failed(\Exception $exception)
    {
        Helper::triggerNotification($exception);
    }
}
