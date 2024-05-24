<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
//        return [
//            'id' => $this->id,
//            'title' => $this->title,
//            'description' => $this->description,
//            'image' => $this->image,
//            'group_id' => $this->group_id,
//            'slug' => $this->slug,
//            'modules' => ModuleResource::collection($this->whenLoaded('modules')),
//        ];
    }
}
