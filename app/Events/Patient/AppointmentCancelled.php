<?php

namespace App\Events\Patient;

use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AppointmentCancelled implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $appointment, $notification, $unread_notifications_count, $total_notifications;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($appointment, $notification, $unread_notifications_count, $total_notifications)
    {
        $this->appointment = $appointment;
        $this->notification = $notification;
        $this->unread_notifications_count = $unread_notifications_count;
        $this->total_notifications = $total_notifications;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn(): Channel|array
    {
        return new Channel('appointment-cancelled-patient');
    }

    public function broadcastAs(): string
    {
        return 'AppointmentCancelledPatient'.'-'.$this->appointment->patient->patient_key;
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->notification->id,
            'appointment_id' => $this->appointment->id,
            'un_read_notifications_count' => $this->unread_notifications_count,
            'total_notifications' => $this->total_notifications,
            'title' => 'Appointment Cancelled - ' . $this->appointment->appointment_key,
            'body' => 'Your appointment has been scheduled with ' . $this->appointment->doctor->name .
                ' on ' . Carbon::parse($this->appointment->date)->format('d-m-Y'). ' - ' . 'has been cancelled.',
            'type' => 'appointment-cancelled',
        ];
    }
}
