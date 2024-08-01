<?php

namespace App\Jobs\Practice;

use App\Events\Practice\DoctorRejectedInvitation;
use App\Helper\Helper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DoctorRejectedInvitationSentByPracticeNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public int $tries = 3;

    public $practiceRequest, $notification, $unread_notifications_count, $total_notifications;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($practiceRequest, $notification, $unread_notifications_count, $total_notifications)
    {
        $this->practiceRequest = $practiceRequest;
        $this->notification = $notification;
        $this->unread_notifications_count = $unread_notifications_count;
        $this->total_notifications = $total_notifications;
        $this->onQueue(config('constants.DOCTOR_REJECTED_INVITATION_SENT_BY_PRACTICE_NOTIFICATION'));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        event(new DoctorRejectedInvitation($this->practiceRequest, $this->notification, $this->unread_notifications_count, $this->total_notifications));
    }

    public function failed(\Exception $exception)
    {
        Helper::triggerNotification($exception);
    }
}
