<?php

namespace App\Repositories;

use App\Models\Chat;
use App\Repositories\BaseRepository;

class ChatRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'user_id',
        'sender_id'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Chat::class;
    }
}
