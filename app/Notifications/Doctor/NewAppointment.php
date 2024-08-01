<?php

namespace App\Notifications\Doctor;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewAppointment extends Notification
{
    use Queueable;

    protected $appointment;

    /**
     * Create a new notification instance.
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
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'appointment_id' => $this->appointment->id,
            'title' => 'Appointment Setup - ' . $this->appointment->appointment_key,
            'body' => 'Your appointment has been scheduled with ' . $this->appointment->patient->name .
                ' on ' . Carbon::parse($this->appointment->date)->format('d-m-Y') . ' - ' .
                Carbon::parse($this->appointment->start_time)->format('g:i A'),
            'type' => 'appointment-setup',
        ];
    }
}
