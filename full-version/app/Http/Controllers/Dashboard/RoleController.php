<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\RoleService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function __construct(
        private readonly RoleService $roleService
    ) {}

    public function index()
    {
        $roles = $this->roleService->getFormattedRoles();
        $stats = $this->roleService->getRolesStats();

        return view('dashboard.roles.index', compact('roles', 'stats'));
    }

    public function create()
    {
        $matrix = $this->roleService->getGroupedPermissions();
        $role = null;
        return view('dashboard.roles.form-modal', compact('role', 'matrix'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'required|array',
        ]);

        $this->roleService->createRole($request->only('name', 'permissions'));

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء الدور بنجاح'
        ]);
    }

    public function show(Role $role)
    {
        $role->load(['permissions', 'users']);
        $colors = ['primary', 'success', 'warning', 'info', 'danger', 'secondary'];
        
        $formattedRole = [
            'id' => $role->id,
            'name' => $role->name,
            'name_ar' => __($role->name),
            'color' => $colors[$role->id % count($colors)],
            'icon' => 'tabler-shield',
            'is_system' => in_array($role->name, ['Super Admin', 'Admin']),
            'description' => 'صلاحيات ' . __($role->name),
            'permissions' => $role->name === 'Super Admin' ? 'all' : $role->permissions->pluck('name')->toArray(),
            'users_count' => $role->users->count(),
        ];

        $assignedUsers = $role->users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => substr($user->name, 0, 2),
            ];
        })->toArray();

        $unassignedAdmins = \App\Models\Admin::whereDoesntHave('roles', function ($query) use ($role) {
            $query->where('id', $role->id);
        })->get(['id', 'name'])->toArray();
        
        $matrix = $this->roleService->getGroupedPermissions();
        return view('dashboard.roles.show', [
            'role' => $formattedRole, 
            'matrix' => $matrix, 
            'assignedUsers' => $assignedUsers,
            'unassignedAdmins' => $unassignedAdmins
        ]);
    }

    public function edit(Role $role)
    {
        $role->load('permissions');
        $colors = ['primary', 'success', 'warning', 'info', 'danger', 'secondary'];
        
        $formattedRole = [
            'id' => $role->id,
            'name' => $role->name,
            'name_ar' => __($role->name),
            'color' => $colors[$role->id % count($colors)],
            'description' => 'صلاحيات ' . __($role->name),
            'permissions' => $role->permissions->pluck('name')->toArray(),
        ];
        
        $matrix = $this->roleService->getGroupedPermissions();
        return view('dashboard.roles.form-modal', ['role' => $formattedRole, 'matrix' => $matrix]);
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'permissions' => 'required|array',
        ]);

        $this->roleService->updateRole($role, $request->only('name', 'permissions'));

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الدور بنجاح'
        ]);
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'Super Admin' || $role->users()->count() > 0) {
            return response()->json(['success' => false, 'message' => 'لا يمكن حذف هذا الدور'], 400);
        }

        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الدور بنجاح'
        ]);
    }

    public function assignUser(Request $request, Role $role)
    {
        $request->validate([
            'admin_id' => 'required|exists:admins,id'
        ]);

        $admin = \App\Models\Admin::findOrFail($request->admin_id);
        $admin->assignRole($role);

        return response()->json(['success' => true, 'message' => 'تم تعيين المشرف للدور بنجاح']);
    }

    public function removeUser(Role $role, \App\Models\Admin $admin)
    {
        if ($role->name === 'Super Admin' && $admin->id === auth('admin')->id()) {
            return response()->json(['success' => false, 'message' => 'لا يمكنك إزالة صلاحية Super Admin من حسابك الشخصي'], 400);
        }
        
        $admin->removeRole($role);
        return response()->json(['success' => true, 'message' => 'تم إزالة المشرف من الدور بنجاح']);
    }
}
