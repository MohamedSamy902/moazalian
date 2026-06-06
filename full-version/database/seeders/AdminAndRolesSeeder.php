<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminAndRolesSeeder extends Seeder
{
    /**
     * All permissions grouped by resource.
     * Format: 'resource.action'
     */
    private array $permissionGroups = [
        'admins'          => ['view', 'create', 'edit', 'delete'],
        'users'           => ['view', 'create', 'edit', 'delete'],
        'roles'           => ['view', 'create', 'edit', 'delete'],
        'videos'          => ['view', 'create', 'edit', 'delete'],
        'courses'         => ['view', 'create', 'edit', 'delete'],
        'sections'        => ['view', 'edit'],
        'quick-responses' => ['view', 'create', 'edit', 'delete'],
    ];

    public function run(): void
    {
        // 1. Create all permissions
        $allPermissions = [];
        foreach ($this->permissionGroups as $resource => $actions) {
            foreach ($actions as $action) {
                $permission = Permission::firstOrCreate([
                    'name'       => "{$resource}.{$action}",
                    'guard_name' => 'admin',
                ]);
                $allPermissions[] = $permission->name;
            }
        }

        // 2. Create Super Admin role with ALL permissions
        $superAdminRole = Role::firstOrCreate([
            'name'       => 'Super Admin',
            'guard_name' => 'admin',
        ]);
        $superAdminRole->syncPermissions($allPermissions);

        // 3. Create Editor role with content permissions only
        $editorRole = Role::firstOrCreate([
            'name'       => 'Editor',
            'guard_name' => 'admin',
        ]);
        $editorRole->syncPermissions([
            'videos.view', 'videos.create', 'videos.edit',
            'courses.view', 'courses.create', 'courses.edit',
            'quick-responses.view', 'quick-responses.create', 'quick-responses.edit',
            'sections.view', 'sections.edit',
        ]);

        // 4. Create the Super Admin account
        $admin = Admin::firstOrCreate(
            ['email' => 'admin@moazalian.com'],
            [
                'name'     => 'Moaz Alian',
                'password' => Hash::make('Admin@1234'),
            ]
        );
        $admin->assignRole($superAdminRole);

        $this->command->info('✅ Admins, Roles & Permissions seeded successfully.');
    }
}
