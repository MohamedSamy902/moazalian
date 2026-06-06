<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\SectionService;
use App\Traits\ApiResponse;
use App\Traits\ChecksPermissions;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    use ChecksPermissions, ApiResponse;

    public function __construct(
        private readonly SectionService $sectionService
    ) {}

    public function index()
    {
        $this->checkPermission('sections.view');
        $sections = $this->sectionService->getGroupedSections();
        return view('dashboard.sections.index', compact('sections'));
    }

    public function edit(string $section_name)
    {
        $this->checkPermission('sections.edit');

        [$keys, $name_ar] = $this->sectionService->getSectionForEdit($section_name);

        return view('dashboard.sections.edit', compact('keys', 'section_name', 'name_ar'));
    }

    public function update(Request $request, string $section_name)
    {
        $this->checkPermission('sections.edit');

        $this->sectionService->updateSection($section_name, $request);

        return redirect()
            ->route('admin.sections.index')
            ->with('success', __('Section updated successfully'));
    }
}
