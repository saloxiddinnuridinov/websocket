<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
      //  return parent::toArray($request);
//        return [
//           // 'id' => $this->id,
//            'name' => $this->name,
//            'group_type' => $this->group_type,
//            'group_price' => $this->group_price,
//            'description' => $this->description,
//            'username' => $this->username,
//            'image' => $this->image,
//        ];
        return [
            'id' => $this->id,
            'name' => $this->name,
            'group_type' => $this->group_type,
            'group_price' => $this->group_price,
            'description' => $this->description,
            'username' => $this->username,
            'image' => $this->image,
        ];

    }
}
