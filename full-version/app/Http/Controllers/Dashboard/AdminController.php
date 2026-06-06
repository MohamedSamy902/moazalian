<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Admins\AdminRequest;
use App\Models\Admin;
use App\Services\Dashboard\AdminService;
use App\Traits\ApiResponse;
use App\Traits\ChecksPermissions;

class AdminController extends Controller
{
    use ChecksPermissions, ApiResponse;

    public function __construct(
        private readonly AdminService $adminService
    ) {}

    public function index()
    {
        $this->checkPermission('admins.view');
        $admins = $this->adminService->getAdminsWithRoles();
        return view('dashboard.admins.index', compact('admins'));
    }

    public function create()
    {
        $this->checkPermission('admins.create');
        $admin = null;
        $roles = $this->adminService->getAllRoles();
        return view('dashboard.admins.form-modal', compact('admin', 'roles'));
    }

    public function store(AdminRequest $request)
    {
        $this->checkPermission('admins.create');

        $this->adminService->createAdmin($request->validated());

        return $this->successResponse('تم إضافة المشرف بنجاح');
    }

    public function edit(Admin $admin)
    {
        /** @var \App\Models\Admin $authAdmin */
        $authAdmin = auth('admin')->user();
        abort_if(
            $authAdmin->id !== $admin->id
                && !$authAdmin->hasRole('Super Admin')
                && !$authAdmin->can('admins.edit'),
            403,
            'غير مصرح لك.'
        );

        $admin->load('roles');
        $roles = $this->adminService->getAllRoles();
        return view('dashboard.admins.form-modal', compact('admin', 'roles'));
    }

    public function update(AdminRequest $request, Admin $admin)
    {
        /** @var \App\Models\Admin $authAdmin */
        $authAdmin = auth('admin')->user();
        abort_if(
            $authAdmin->id !== $admin->id
                && !$authAdmin->hasRole('Super Admin')
                && !$authAdmin->can('admins.edit'),
            403,
            'غير مصرح لك.'
        );

        $this->adminService->updateAdmin($admin, $request->validated());

        return $this->successResponse('تم تحديث بيانات المشرف بنجاح');
    }

    public function destroy(Admin $admin)
    {
        $this->checkPermission('admins.delete');

        try {
            $this->adminService->deleteAdmin($admin);
            return $this->successResponse('تم حذف المشرف بنجاح');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
