<?php

namespace App\Http\Resources\V1;

use App\Http\Helpers\Encryption;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Crypt;

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
        $decryptedText = null;
        try {
            $decryptedText = Crypt::decrypt($this->text);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            \Log::error('Decryption error: ' . $e->getMessage());
        }

        return [
            'id' => $this->id,
            'sender' => $this->senderId,
            'chat_id' => $this->chat_id,
            'text' => $decryptedText,
            'is_read' => $this->is_read,
            'type' => $this->type,
            'file_url' => $this->file_url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];


//        return [
//            'id' => $this->id,
//            'sender' => $this->senderId,
//            'chat_id' => $this->chat_id,
//            'text' => Crypt::decrypt($this->text),
//            'is_read' => $this->is_read,
//            'type' => $this->type,
//            'file_url' => $this->file_url,
//            'created_at' => $this->created_at,
//            'updated_at' => $this->updated_at,
//        ];
    }
}
