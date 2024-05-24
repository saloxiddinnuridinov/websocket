<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name',
        'surname',
        'url',
        'bio',
        'address',
        'myers_briggs',
        'website_link',
        'instagram_link',
        'twitter_link',
        'youtube_link',
        'linkedin_link',
        'facebook_link',
        'email',
        'password',
        'timezone'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return User::class;
    }
}
