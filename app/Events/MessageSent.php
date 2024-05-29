<?php

namespace App\Events;

use App\Http\Helpers\Encryption;
use App\Http\Resources\V1\Websocket\MessageResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $message;

    public function __construct($message)
    {
        $this->message = $message;
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
        $chId = $this->message->chat_id;
        $r = Encryption::encryptMessage($this->message->text);
        echo "Message broadcastOn funksiyasi. ChatID: " .Encryption::decryptMessage($this->message->text);
//        echo "Message broadcastOn funksiyasi. ChatID: " . Encryption::decryptMessage($this->message->text);
        new Channel('chat_' .$chId);
    }
}
