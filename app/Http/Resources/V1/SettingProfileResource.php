<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $socials = [
            'website' => $this->website_link !== "null" ? $this->website_link : '',
            'instagram' => $this->instagram_link !== "null" ? $this->instagram_link : '',
            'twitter' => $this->twitter_link !== "null" ? $this->twitter_link : '',
            'youtube' => $this->youtube_link !== "null" ? $this->youtube_link : '',
            'linkedin' => $this->linkedin_link !== "null" ? $this->linkedin_link : '',
            'facebook' => $this->facebook_link !== "null" ? $this->facebook_link : '',
        ];

        return [
            'id' => $this->id,
            'name' => $this->name,
            'surname' => $this->surname,
            'email' => $this->email,
            'username' => $this->username,
            'bio' => $this->bio,
            'location' => $this->address,
            'image' => $this->image,
            'myers_briggs' => $this->myers_briggs,
            'socials' => $socials,
            'creator' => $this->creator,
            'member' => $this->member

        ];
    }
}
