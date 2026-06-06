<?php

namespace App\Repositories\Implementations;

use App\Models\Course;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class CourseRepository extends BaseRepository implements CourseRepositoryInterface
{
    public function __construct(Course $model)
    {
        parent::__construct($model);
    }

    public function getPublishedPaginated(int $perPage = 15)
    {
        return $this->model->published()->latest()->paginate($perPage);
    }

    public function findBySlugWithLessons(string $slug): ?Model
    {
        return $this->model->where('slug', $slug)
                           ->with(['lessons' => fn($q) => $q->orderBy('number')])
                           ->first();
    }
}
