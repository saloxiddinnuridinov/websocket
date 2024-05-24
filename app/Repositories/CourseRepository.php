<?php

namespace App\Repositories;

use App\Models\Course;
use App\Repositories\BaseRepository;

class CourseRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'title',
        'description',
        'image'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Course::class;
    }

    public function getClassRoom($column, $value)
    {
        return Course::where($column, $value)->paginate(15)->load('set');
    }
}
