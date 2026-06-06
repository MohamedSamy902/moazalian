<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface CourseRepositoryInterface extends BaseRepositoryInterface
{
    public function getPublishedPaginated(int $perPage = 15);
    public function findBySlugWithLessons(string $slug): ?Model;
}
