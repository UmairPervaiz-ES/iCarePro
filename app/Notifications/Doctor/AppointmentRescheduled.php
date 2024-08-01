<?php

namespace App\Notifications\Doctor;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentRescheduled extends Notification
{
    use Queueable;

    protected $previousAppointment, $appointment;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($previousAppointment, $appointment)
    {
        $this->previousAppointment = $previousAppointment;
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
            'title' => 'Appointment Rescheduled - ' . $this->appointment->appointment_key,
            'body' => 'Your appointment has been rescheduled with ' . $this->appointment->patient->name,
            'new_time' =>  Carbon::parse($this->appointment->date)->format('d-m-Y'). ' - ' .  Carbon::parse($this->appointment->start_time)->format('g:i A'),
            'old_time' => Carbon::parse($this->previousAppointment->date)->format('d-m-Y'). ' - ' .  Carbon::parse($this->previousAppointment->start_time)->format('g:i A'),
            'type' => 'appointment-rescheduled',
        ];
    }
}
