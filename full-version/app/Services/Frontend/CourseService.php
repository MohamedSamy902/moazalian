<?php
namespace App\Services\Frontend;

use App\DTOs\Frontend\CommentDTO;
use App\Repositories\Interfaces\CourseRepositoryInterface;

class CourseService
{
    public function __construct(
        private readonly CourseRepositoryInterface $courseRepo,
    ) {}

    public function getCourseDetails(string $slug)
    {
        return $this->courseRepo->findBySlugWithLessons($slug);
    }

    public function getPaginated(array $filters = [])
    {
        $query = \App\Models\Course::published()->latest();

        if (!empty($filters['search'])) {
            $search = mb_substr(strip_tags($filters['search']), 0, 100);
            $query->where(function ($q) use ($search) {
                $q->where('title->ar', 'like', "%{$search}%")
                  ->orWhere('title->en', 'like', "%{$search}%");
            });
        }

        return $query->withCount('lessons')->paginate(12)->withQueryString();
    }
}
