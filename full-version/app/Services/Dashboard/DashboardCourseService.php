<?php

namespace App\Services\Dashboard;

use App\Models\Course;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use Illuminate\Support\Str;

class DashboardCourseService
{
    public function __construct(
        private readonly CourseRepositoryInterface $courseRepo,
    ) {}

    /**
     * Get paginated courses with optional search filter.
     */
    public function getCourses(array $filters = [])
    {
        $query = Course::query();

        if (!empty($filters['search'])) {
            $search = mb_substr(strip_tags($filters['search']), 0, 100);
            $query->where(function ($q) use ($search) {
                $q->where('title->ar', 'like', "%{$search}%")
                  ->orWhere('title->en', 'like', "%{$search}%");
            });
        }

        if (isset($filters['status']) && $filters['status'] === 'published') {
            $query->where('is_published', true);
        } elseif (isset($filters['status']) && $filters['status'] === 'draft') {
            $query->where('is_published', false);
        }

        return $query->latest()->paginate(15)->withQueryString();
    }

    /**
     * Create a new course with auto-generated slug.
     */
    public function storeCourse(array $data): Course
    {
        $data['slug'] = $this->generateSlug($data['title']['ar'] ?? 'course', $data['slug'] ?? null);

        return $this->courseRepo->create($data);
    }

    /**
     * Update an existing course.
     */
    public function updateCourse(Course $course, array $data): Course
    {
        if (empty($data['slug'])) {
            $data['slug'] = $course->slug; // Keep existing slug
        }

        $this->courseRepo->update($course, $data);

        return $course->refresh();
    }

    /**
     * Soft-delete a course.
     */
    public function deleteCourse(Course $course): bool
    {
        return $this->courseRepo->delete($course);
    }

    /**
     * Generate a unique slug from Arabic/English title.
     */
    private function generateSlug(string $title, ?string $preferredSlug = null): string
    {
        $slug = $preferredSlug
            ? Str::slug($preferredSlug)
            : Str::slug($title) ?: 'course-' . uniqid();

        // Ensure uniqueness
        $original = $slug;
        $count    = 1;
        while (Course::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $count++;
        }

        return $slug;
    }
}
