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
    
    // Will add Pipeline logic for filtering courses later
}
