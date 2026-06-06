<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Frontend\CourseService;

class CourseController extends Controller
{
    public function __construct(
        private readonly CourseService $courseService
    ) {}

    public function index()
    {
        // Will implement pipeline logic to filter courses later
        return view('frontend.courses');
    }

    public function show(string $slug)
    {
        $course = $this->courseService->getCourseDetails($slug);
        
        abort_if(!$course, 404);

        return view('frontend.course-details', compact('course'));
    }
}
