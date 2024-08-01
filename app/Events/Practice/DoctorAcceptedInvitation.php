<?php

namespace App\Events\Practice;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DoctorAcceptedInvitation implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public int $tries = 3;

    public $doctorPractice, $doctor_name, $notification, $unread_notifications_count, $total_notifications;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($doctorPractice, $notification, $unread_notifications_count, $total_notifications)
    {
        $this->doctorPractice = $doctorPractice;
        $this->notification = $notification;
        $this->unread_notifications_count = $unread_notifications_count;
        $this->total_notifications = $total_notifications;
        $this->doctor_name = $doctorPractice->doctor->first_name . ' ' . $doctorPractice->doctor->middle_name . ' ' . $doctorPractice->doctor_last_name;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn(): Channel|array
    {
        return new Channel('doctor-accepted-invitation');
    }

    public function broadcastAs(): string
    {
        return 'DoctorInvitationAccepted'.'-'.$this->doctorPractice->practice->practice_key;
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->notification->id,
            'doctor_id' => $this->doctorPractice->doctor->id,
            'un_read_notifications_count' => $this->unread_notifications_count,
            'total_notifications' => $this->total_notifications,
            'title' => 'Request Accepted - ' . $this->doctor_name,
            'body' => $this->doctor_name . ' has accepted your invitation.',
            'type' => 'request-accepted',
        ];
    }
}
