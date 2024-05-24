<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'user' => $this->userId,
            'group_type' => $this->group_type,
            'group_price' => $this->group_price,
            'description' => $this->description,
            'username' => $this->username,
            'created' => date('Y-m-d H:i:s', strtotime($this->created_at)),
            'members' => $this->member,
        ];
    }
}
