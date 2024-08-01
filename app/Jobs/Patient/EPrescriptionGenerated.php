<?php

namespace App\Jobs\Patient;

use App\Helper\Helper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EPrescriptionGenerated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $appointment, $notification, $unread_notifications_count, $total_notifications;

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
        $this->onQueue(config('constants.E_PRESCRIPTION_GENERATED'));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        event(new \App\Events\Patient\EPrescriptionGenerated($this->appointment, $this->notification, $this->unread_notifications_count, $this->total_notifications));
    }

    public function failed(\Exception $exception)
    {
        Helper::triggerNotification($exception);
    }
}
