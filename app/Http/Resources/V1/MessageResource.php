<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

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
//        return [
//            'chat_messages' => $this->collection->toArray(),
//        ];
        return [
            'id' => $this->id,
            'sender' => $this->senderId,
            'chat_id' => $this->chat_id,
            'text' => $this->text,
            'is_read' => $this->is_read,
            'type' => $this->type,
            'file_url' => $this->file_url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
