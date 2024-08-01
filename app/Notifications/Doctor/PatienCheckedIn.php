<?php

namespace App\Notifications\Doctor;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PatienCheckedIn extends Notification
{
    use Queueable;

    protected $appointment;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($appointment)
    {
        $this->appointment = $appointment;
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
            'appointment_id' => $this->appointment->id,
            'title' => 'Patient check-in ' . $this->appointment->patient->name,
            'body' => 'Patient ' . $this->appointment->patient->name . ' has checked-in for appointment #: ' . $this->appointment->appointment_key,
            'type' => 'patient-checked-in',
        ];
    }
}
