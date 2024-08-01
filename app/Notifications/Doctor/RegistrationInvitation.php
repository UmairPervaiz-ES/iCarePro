<?php

namespace App\Notifications\Doctor;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RegistrationInvitation extends Notification
{
    use Queueable;

    private $practice,$practice_name,$doctor;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($practice, $doctor)
    {
        $this->doctor = $doctor;
        $this->practice = $practice;
        $this->practice_name = $practice->initialPractice->practice_name;
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
            'title' => 'Registration Request - ' . $this->practice_name,
            'body' => $this->practice_name . ' has invited you to get register for their practice.',
            'type' => 'registration-request',
        ];
    }
}
