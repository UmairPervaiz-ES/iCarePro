<?php

namespace App\Events\Practice;

use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendDoctorRegistrationInvitation implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $practice_name, $doctor, $notification, $unread_notifications_count, $total_notifications;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($practice, $doctor, $notification, $unread_notifications_count, $total_notifications)
    {
        $this->practice_name = $practice->initialPractice->practice_name;
        $this->doctor = $doctor;
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
        return new Channel('send-doctor-registration-invitation');
    }

    public function broadcastAs(): string
    {
        return 'SendDoctorRegistrationInvitation'.'-'.$this->doctor->doctor_key;
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->notification->id,
            'un_read_notifications_count' => $this->unread_notifications_count,
            'total_notifications' => $this->total_notifications,
            'title' => 'Registration Request - ' . $this->practice_name,
            'body' => $this->practice_name . ' has invited you to get register for their practice.',
            'type' => 'registration-request',
        ];
    }
}
