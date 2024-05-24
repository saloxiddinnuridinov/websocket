<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ModuleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
      //  return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'position' => $this->position,
            'lessons' => LessonResource::collection($this->lessons),
        ];
    }
}
