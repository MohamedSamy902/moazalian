<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\SearchRequest;
use App\Services\Frontend\CourseService;

class CourseController extends Controller
{
    public function __construct(
        private readonly CourseService $courseService,
    ) {}

    public function index(SearchRequest $request)
    {
        $courses = $this->courseService->getPaginated($request->validated());

        return view('frontend.courses.index', compact('courses'));
    }

    public function show(string $slug)
    {
        $course = $this->courseService->getCourseDetails($slug);
        abort_if(!$course, 404);

        return view('frontend.courses.show', compact('course'));
    }
}
