<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'slug' => $this->slug,
//            'category_id' => $this->category_id,
          //  'group_id' => $this->group_id,
            'user' => $this->userID,
            'group_pinned' => $this->group_pinned,
            'system_pinned' => $this->system_pinned,
            'joined' => $this->joined,
            'media_files' => $this->media_files,
        ];
    }
}
