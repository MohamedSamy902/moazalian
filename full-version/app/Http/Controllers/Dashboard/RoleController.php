<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Roles\RoleRequest;
use App\Models\Admin;
use App\Services\Dashboard\RoleService;
use App\Traits\ApiResponse;
use App\Traits\ChecksPermissions;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    use ChecksPermissions, ApiResponse;

    public function __construct(
        private readonly RoleService $roleService
    ) {}

    public function index()
    {
        $this->checkPermission('roles.view');
        $roles = $this->roleService->getFormattedRoles();
        $stats = $this->roleService->getRolesStats();
        return view('dashboard.roles.index', compact('roles', 'stats'));
    }

    public function create()
    {
        $this->checkPermission('roles.create');
        $matrix = $this->roleService->getGroupedPermissions();
        $role   = null;
        return view('dashboard.roles.form-modal', compact('role', 'matrix'));
    }

    public function store(RoleRequest $request)
    {
        $this->checkPermission('roles.create');

        $this->roleService->createRole($request->validated());

        return $this->successResponse('تم إنشاء الدور بنجاح');
    }

    public function show(Role $role)
    {
        $this->checkPermission('roles.view');

        $role->load(['permissions', 'users']);
        $colors = ['primary', 'success', 'warning', 'info', 'danger', 'secondary'];

        $formattedRole = [
            'id'          => $role->id,
            'name'        => $role->name,
            'name_ar'     => __($role->name),
            'color'       => $colors[$role->id % count($colors)],
            'icon'        => 'tabler-shield',
            'is_system'   => in_array($role->name, ['Super Admin', 'Admin']),
            'description' => 'صلاحيات ' . __($role->name),
            'permissions' => $role->name === 'Super Admin' ? 'all' : $role->permissions->pluck('name')->toArray(),
            'users_count' => $role->users->count(),
        ];

        $assignedUsers = $role->users->map(fn ($user) => [
            'id'     => $user->id,
            'name'   => $user->name,
            'email'  => $user->email,
            'avatar' => substr($user->name, 0, 2),
        ])->toArray();

        $unassignedAdmins = Admin::whereDoesntHave('roles', function ($query) use ($role) {
            $query->where('id', $role->id);
        })->get(['id', 'name'])->toArray();

        $matrix = $this->roleService->getGroupedPermissions();

        return view('dashboard.roles.show', [
            'role'             => $formattedRole,
            'matrix'           => $matrix,
            'assignedUsers'    => $assignedUsers,
            'unassignedAdmins' => $unassignedAdmins,
        ]);
    }

    public function edit(Role $role)
    {
        $this->checkPermission('roles.edit');

        $role->load('permissions');
        $colors = ['primary', 'success', 'warning', 'info', 'danger', 'secondary'];

        $formattedRole = [
            'id'          => $role->id,
            'name'        => $role->name,
            'name_ar'     => __($role->name),
            'color'       => $colors[$role->id % count($colors)],
            'description' => 'صلاحيات ' . __($role->name),
            'permissions' => $role->permissions->pluck('name')->toArray(),
        ];

        $matrix = $this->roleService->getGroupedPermissions();

        return view('dashboard.roles.form-modal', ['role' => $formattedRole, 'matrix' => $matrix]);
    }

    public function update(RoleRequest $request, Role $role)
    {
        $this->checkPermission('roles.edit');

        $this->roleService->updateRole($role, $request->validated());

        return $this->successResponse('تم تحديث الدور بنجاح');
    }

    public function destroy(Role $role)
    {
        $this->checkPermission('roles.delete');

        if ($role->name === 'Super Admin' || $role->users()->count() > 0) {
            return $this->errorResponse('لا يمكن حذف هذا الدور');
        }

        $role->delete();

        return $this->successResponse('تم حذف الدور بنجاح');
    }

    public function assignUser(Request $request, Role $role)
    {
        $this->checkPermission('roles.edit');

        $request->validate([
            'admin_id' => 'required|exists:admins,id',
        ]);

        $admin = Admin::findOrFail($request->admin_id);
        $admin->assignRole($role);

        return $this->successResponse('تم تعيين المشرف للدور بنجاح');
    }

    public function removeUser(Role $role, Admin $admin)
    {
        $this->checkPermission('roles.edit');

        if ($role->name === 'Super Admin' && $admin->id === auth('admin')->id()) {
            return $this->errorResponse('لا يمكنك إزالة صلاحية Super Admin من حسابك الشخصي');
        }

        $admin->removeRole($role);

        return $this->successResponse('تم إزالة المشرف من الدور بنجاح');
    }
}
