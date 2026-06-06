<?php
namespace App\Services\Dashboard;

use App\Repositories\Interfaces\CourseRepositoryInterface;

class DashboardCourseService
{
    public function __construct(
        private readonly CourseRepositoryInterface $courseRepo,
    ) {}

    public function storeCourse(array $data)
    {
        // Pipeline filtering or complex logic would go here
        return $this->courseRepo->create($data);
    }
}
