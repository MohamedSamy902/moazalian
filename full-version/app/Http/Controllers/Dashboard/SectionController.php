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

    /**
     * Edit a specific section by page + section_name.
     * URL: /admin/sections/{page}/{section_name}/edit
     */
    public function edit(string $page, string $section_name)
    {
        $this->checkPermission('sections.edit');

        [$keys, $name_ar] = $this->sectionService->getSectionForEdit($page, $section_name);

        return view('dashboard.sections.edit', compact('keys', 'section_name', 'page', 'name_ar'));
    }

    /**
     * Save section changes.
     * URL: PUT /admin/sections/{page}/{section_name}
     */
    public function update(Request $request, string $page, string $section_name)
    {
        $this->checkPermission('sections.edit');

        $this->sectionService->updateSection($page, $section_name, $request);

        return redirect()
            ->route('admin.sections.index')
            ->with('success', __('Section updated successfully'));
    }
}
