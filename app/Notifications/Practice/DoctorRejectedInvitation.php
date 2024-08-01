<?php

namespace App\Notifications\Practice;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DoctorRejectedInvitation extends Notification
{
    use Queueable;

    protected $practiceRequest, $doctor_name;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($practiceRequest)
    {
        $this->practiceRequest = $practiceRequest;
        $this->doctor_name = $practiceRequest->doctor->first_name . ' ' . $practiceRequest->doctor->middle_name . ' ' . $practiceRequest->doctor->last_name;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title' => 'Request Rejected - ' . $this->doctor_name,
            'body' => $this->doctor_name . ' has rejected your invitation.',
            'type' => 'request-rejected',
        ];
    }
}
