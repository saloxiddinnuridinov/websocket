<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LevelResource extends JsonResource
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
            'level' => $this->name,
            'custom_name' => $this->custom_name,
            'points' => $this->points,
            'completed' => $this->completed,
            'created' => date('Y-m-d H:i:s', strtotime($this->created_at)),
        ];
    }
}
