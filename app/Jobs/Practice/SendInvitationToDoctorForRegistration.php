<?php

namespace App\Jobs\Practice;

use App\Events\Practice\SendDoctorRegistrationInvitation;
use App\Helper\Helper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendInvitationToDoctorForRegistration implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public int $tries = 3;

    public $doctorPractice, $notification, $unread_notifications_count, $total_notifications;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($doctorPractice, $notification, $unread_notifications_count, $total_notifications)
    {
        $this->doctorPractice = $doctorPractice;
        $this->notification = $notification;
        $this->unread_notifications_count = $unread_notifications_count;
        $this->total_notifications = $total_notifications;
        $this->onQueue(config('constants.SEND_INVITATION_TO_DOCTOR_NOTIFICATION'));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        event(new SendDoctorRegistrationInvitation($this->doctorPractice->practice, $this->doctorPractice->doctor, $this->notification, $this->total_notifications, $this->unread_notifications_count));
    }

    public function failed(\Exception $exception)
    {
        Helper::triggerNotification($exception);
    }
}
