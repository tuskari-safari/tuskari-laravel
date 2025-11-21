<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $room_id, $user, $message;

    /**
     * Create a new event instance.
     */
    public function __construct($room_id, $user, $message)
    {
        $this->message = $message;
        $this->user = $user;
        $this->room_id = $room_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
   

    public function broadcastWith()
    {
        return ['user' => $this->user, 'message' => $this->message];
    }
    
    
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('send-message.' . $this->room_id.'-'.$this->user->id),
        ];
    }
}
