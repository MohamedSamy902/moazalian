<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Services\Dashboard\AdminService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct(
        private readonly AdminService $adminService
    ) {}

    private function checkPermission($permission)
    {
        /** @var \App\Models\Admin $user */
        $user = auth('admin')->user();
        abort_if(!$user->hasRole('Super Admin') && !$user->can($permission), 403, 'غير مصرح لك');
    }

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

    public function store(Request $request)
    {
        $this->checkPermission('admins.create');
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:6',
            'roles' => 'nullable|array',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $this->adminService->createAdmin($request->all());

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة المشرف بنجاح'
        ]);
    }

    public function edit(Admin $admin)
    {
        /** @var \App\Models\Admin $user */
        $user = auth('admin')->user();
        abort_if($user->id !== $admin->id && !$user->hasRole('Super Admin') && !$user->can('admins.edit'), 403, 'غير مصرح لك');
        
        $admin->load('roles');
        $roles = $this->adminService->getAllRoles();
        return view('dashboard.admins.form-modal', compact('admin', 'roles'));
    }

    public function update(Request $request, Admin $admin)
    {
        /** @var \App\Models\Admin $user */
        $user = auth('admin')->user();
        abort_if($user->id !== $admin->id && !$user->hasRole('Super Admin') && !$user->can('admins.edit'), 403, 'غير مصرح لك');

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'password' => 'nullable|string|min:6',
            'roles' => 'nullable|array',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $this->adminService->updateAdmin($admin, $request->all());

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث بيانات المشرف بنجاح'
        ]);
    }

    public function destroy(Admin $admin)
    {
        $this->checkPermission('admins.delete');
        try {
            $this->adminService->deleteAdmin($admin);
            return response()->json([
                'success' => true,
                'message' => 'تم حذف المشرف بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
