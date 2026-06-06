<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Courses\CourseRequest;
use App\Models\Course;
use App\Services\Dashboard\DashboardCourseService;
use App\Traits\ApiResponse;
use App\Traits\ChecksPermissions;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    use ChecksPermissions, ApiResponse;

    public function __construct(
        private readonly DashboardCourseService $courseService
    ) {}

    public function index(Request $request)
    {
        $this->checkPermission('courses.view');
        $filters = $request->only(['search', 'status']);
        $courses = $this->courseService->getCourses($filters);
        return view('dashboard.courses.index', compact('courses'));
    }

    public function create()
    {
        $this->checkPermission('courses.create');
        $course = null;
        return view('dashboard.courses.form-modal', compact('course'));
    }

    public function store(CourseRequest $request)
    {
        $this->checkPermission('courses.create');

        $this->courseService->storeCourse($request->validated());

        return $this->successResponse('تم إنشاء الكورس بنجاح');
    }

    public function edit(Course $course)
    {
        $this->checkPermission('courses.edit');
        return view('dashboard.courses.form-modal', compact('course'));
    }

    public function update(CourseRequest $request, Course $course)
    {
        $this->checkPermission('courses.edit');

        $this->courseService->updateCourse($course, $request->validated());

        return $this->successResponse('تم تحديث الكورس بنجاح');
    }

    public function destroy(Course $course)
    {
        $this->checkPermission('courses.delete');

        try {
            $this->courseService->deleteCourse($course);
            return $this->successResponse('تم حذف الكورس بنجاح');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
