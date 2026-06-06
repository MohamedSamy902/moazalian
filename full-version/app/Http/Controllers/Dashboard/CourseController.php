<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\DashboardCourseService;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function __construct(
        private readonly DashboardCourseService $courseService
    ) {}

    public function index(Request $request)
    {
        // Pipeline implementation for advanced filtering will be placed in the Service
        // Returning empty view for architecture verification
        return view('dashboard.courses.index');
    }

    public function store(Request $request)
    {
        // Data is strictly validated by FormRequest before reaching here.
        // For demonstration of architecture, using inline validation here:
        $data = $request->validate([
            'title' => 'required|array', // Because of Spatie Translatable
            'title.ar' => 'required|string',
            'title.en' => 'nullable|string',
        ]);

        $this->courseService->storeCourse($data);

        return redirect()->route('admin.courses.index')->with('success', 'تم إنشاء الكورس بنجاح');
    }
}
