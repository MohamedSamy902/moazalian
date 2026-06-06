<?php

namespace App\Services\Dashboard;

use App\Models\Admin;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleService
{
    public function getRolesStats()
    {
        return [
            'total_roles' => Role::count(),
            'total_users' => Admin::has('roles')->count() + (class_exists(User::class) ? User::has('roles')->count() : 0),
            'system_roles' => Role::whereIn('name', ['Super Admin', 'Admin'])->count(),
            'custom_roles' => Role::whereNotIn('name', ['Super Admin', 'Admin'])->count(),
        ];
    }

    public function getFormattedRoles()
    {
        $roles = Role::with('permissions')->withCount('users')->get();
        $formatted = [];
        $colors = ['primary', 'success', 'warning', 'info', 'danger', 'secondary'];

        foreach ($roles as $index => $role) {
            $formatted[] = [
                'id' => $role->id,
                'name' => $role->name,
                'name_ar' => __($role->name), // Simplistic translation approach
                'color' => $colors[$role->id % count($colors)],
                'icon' => 'tabler-shield', // Default icon
                'is_system' => in_array($role->name, ['Super Admin', 'Admin']),
                'description' => 'صلاحيات ' . __($role->name),
                'users_count' => $role->users_count,
                'permissions' => $role->name === 'Super Admin' ? 'all' : $role->permissions->pluck('name')->toArray(),
            ];
        }

        return $formatted;
    }

    public function getGroupedPermissions()
    {
        $permissions = Permission::all();
        $matrix = [];

        $moduleNames = [
            'users' => 'المستخدمين',
            'admins' => 'المشرفين',
            'roles' => 'الصلاحيات',
            'courses' => 'الدورات',
            'articles' => 'المقالات',
            'sections' => 'إعدادات الواجهة',
            'settings' => 'إعدادات النظام',
        ];

        $actionNames = [
            'view' => 'عرض',
            'create' => 'إضافة',
            'edit' => 'تعديل',
            'delete' => 'حذف',
            'publish' => 'نشر',
            'unpublish' => 'إلغاء النشر',
            'manage' => 'إدارة',
            'restore' => 'استعادة',
            'force_delete' => 'حذف نهائي',
        ];

        foreach ($permissions as $permission) {
            $parts = explode('.', $permission->name);
            $moduleKey = $parts[0] ?? 'general';
            $action = $parts[1] ?? $permission->name;

            $moduleNameAr = $moduleNames[$moduleKey] ?? ucfirst($moduleKey);
            $actionNameAr = $actionNames[$action] ?? ucfirst($action);

            if (!isset($matrix[$moduleNameAr])) {
                $matrix[$moduleNameAr] = [$moduleKey, []];
            }

            $matrix[$moduleNameAr][1][$action] = $actionNameAr;
        }

        return $matrix;
    }

    public function createRole(array $data)
    {
        $role = Role::create([
            'name' => $data['name'],
            'guard_name' => 'admin' // Depending on your auth setup
        ]);

        if (!empty($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }

        return $role;
    }

    public function updateRole(Role $role, array $data)
    {
        $role->update([
            'name' => $data['name']
        ]);

        if (isset($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }

        return $role;
    }
}
