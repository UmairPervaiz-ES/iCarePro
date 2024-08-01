<?php

namespace App\Events\Appointment;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

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
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('update-appointment-status');
    }

    public function broadcastAs(): string
    {
        return 'UpdateAppointmentStatus';
    }

    public function broadcastWith()
    {
        return [
            'appointment_key' => $this->appointment->appointment_key,
            'appointment_status' => $this->appointment->status,
        ];
    }
}
