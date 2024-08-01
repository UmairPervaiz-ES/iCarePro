<?php

namespace App\Events\Practice;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DoctorRejectedInvitation implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $practiceRequest, $doctor_name, $notification, $unread_notifications_count, $total_notifications;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($practiceRequest, $notification, $unread_notifications_count, $total_notifications)
    {
        $this->practiceRequest = $practiceRequest;
        $this->notification = $notification;
        $this->unread_notifications_count = $unread_notifications_count;
        $this->total_notifications = $total_notifications;
        $this->doctor_name = $practiceRequest->doctor->first_name . ' ' . $practiceRequest->doctor->middle_name . ' ' . $practiceRequest->doctor->last_name;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn(): Channel|array
    {
        return new Channel('doctor-rejected-invitation');
    }

    public function broadcastAs(): string
    {
        return 'DoctorInvitationRejected'.'-'.$this->practiceRequest->practice->practice_key;
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->notification->id,
            'doctor_id' => $this->practiceRequest->doctor->id,
            'un_read_notifications_count' => $this->unread_notifications_count,
            'total_notifications' => $this->total_notifications,
            'title' => 'Request Rejected - ' . $this->doctor_name,
            'body' => $this->doctor_name . ' has rejected your invitation.',
            'type' => 'request-rejected',
        ];
    }
}
