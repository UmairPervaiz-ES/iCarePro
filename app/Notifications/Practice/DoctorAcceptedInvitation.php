<?php

namespace App\Notifications\Practice;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DoctorAcceptedInvitation extends Notification
{
    use Queueable;

    protected $doctorPractice, $doctor_name;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($doctorPractice)
    {
        $this->doctorPractice = $doctorPractice;
        $this->doctor_name = $doctorPractice->doctor->first_name . ' ' . $doctorPractice->doctor->middle_name . ' ' . $doctorPractice->doctor_last_name;
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
            'title' => 'Request Accepted - ' . $this->doctor_name,
            'body' => $this->doctor_name . ' has accepted your invitation.',
            'type' => 'request-accepted',
        ];
    }
}
