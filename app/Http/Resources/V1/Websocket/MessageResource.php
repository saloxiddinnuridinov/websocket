<?php

namespace App\Http\Resources\V1\Websocket;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'sender' => $this->senderId,
            'chat_id' => $this->chat_id,
            'text' => $this->text,
            'is_read' => boolval($this->is_read),
            'file_url' => $this->file_url,
            'token' => $this->type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'time' => $this->time,
            'full_time' => $this->full_time,
        ];
    }
}
