<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
    //    return parent::toArray($request);
        return [
            'id' => $this->id,
            'title' => $this->title,
            'module_id' => $this->module_id,
            'description' => $this->description,
            'video_files' => $this->video_files,
        ];
    }
}
