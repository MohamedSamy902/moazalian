<?php

namespace App\Services\Dashboard;

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminService
{
    public function getAdminsWithRoles()
    {
        return Admin::with('roles')->paginate(10);
    }

    public function getAllRoles()
    {
        return Role::all();
    }

    public function createAdmin(array $data)
    {
        $adminData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ];

        if (isset($data['avatar'])) {
            $result = \MohamedSamy902\AdvancedFileUpload\Facades\FileUpload::upload($data['avatar'], ['folder_name' => 'admins']);
            $adminData['avatar'] = $result->path;
        }

        $admin = Admin::create($adminData);

        if (!empty($data['roles'])) {
            $admin->syncRoles($data['roles']);
        }

        return $admin;
    }

    public function updateAdmin(Admin $admin, array $data)
    {
        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        if (isset($data['avatar'])) {
            if ($admin->avatar) {
                \MohamedSamy902\AdvancedFileUpload\Facades\FileUpload::delete($admin->avatar);
            }
            $result = \MohamedSamy902\AdvancedFileUpload\Facades\FileUpload::upload($data['avatar'], ['folder_name' => 'admins']);
            $updateData['avatar'] = $result->path;
        }

        $admin->update($updateData);

        if (isset($data['roles'])) {
            $admin->syncRoles($data['roles']);
        } else {
            $admin->syncRoles([]);
        }

        return $admin;
    }

    public function deleteAdmin(Admin $admin)
    {
        if ($admin->id === auth('admin')->id()) {
            throw new \Exception('لا يمكنك حذف حسابك الشخصي');
        }

        if ($admin->hasRole('Super Admin') && Admin::role('Super Admin')->count() === 1) {
            throw new \Exception('لا يمكن حذف آخر مدير نظام في المنصة');
        }

        return $admin->delete();
    }
}
