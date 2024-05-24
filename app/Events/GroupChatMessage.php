<?php

namespace App\Events;

use App\Http\Resources\V1\Websocket\MessageResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GroupChatMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $message;
    public $group_username;

    public function __construct($message, $group_username)
    {
        $this->message = $message;
        $this->group_username = $group_username;
    }

    public function broadcastWith()
    {
        $model = new MessageResource($this->message);
        return [
            'message' => $model
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        echo "Message broadcastOn funksiyasi. ChatID: " . $this->group_username;
        return new Channel('group_chat_' . $this->group_username);
    }
}
