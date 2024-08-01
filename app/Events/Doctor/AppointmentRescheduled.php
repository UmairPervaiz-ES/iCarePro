<?php

namespace App\Events\Doctor;

use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AppointmentRescheduled implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $previousAppointment, $appointment, $notification, $unread_notifications_count, $total_notifications;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($previousAppointment, $appointment, $notification, $unread_notifications_count, $total_notifications)
    {
        $this->previousAppointment = $previousAppointment;
        $this->appointment = $appointment;
        $this->notification = $notification;
        $this->total_notifications = $total_notifications;
        $this->unread_notifications_count = $unread_notifications_count;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn(): Channel|array
    {
        return new Channel('appointment-rescheduled-doctor');
    }

    public function broadcastAs(): string
    {
        return 'AppointmentRescheduledDoctor'.'-'.$this->appointment->doctor->doctor_key;
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->notification->id,
            'appointment_id' => $this->appointment->id,
            'un_read_notifications_count' => $this->unread_notifications_count,
            'total_notifications' => $this->total_notifications,
            'title' => 'Appointment Rescheduled - ' . $this->appointment->appointment_key,
            'body' => 'Your appointment has been rescheduled with ' . $this->appointment->patient->name,
            'new_time' =>  Carbon::parse($this->appointment->date)->format('d-m-Y'). ' - ' .  Carbon::parse($this->appointment->start_time)->format('g:i A'),
            'old_time' => Carbon::parse($this->previousAppointment->date)->format('d-m-Y'). ' - ' .  Carbon::parse($this->previousAppointment->start_time)->format('g:i A'),
            'type' => 'appointment-rescheduled',
        ];
    }
}
