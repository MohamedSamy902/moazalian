<?php

namespace App\Services\Dashboard;

use App\Models\Admin;
use App\Services\Core\ImageService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use MohamedSamy902\AdvancedFileUpload\Facades\FileUpload;
use Spatie\Permission\Models\Role;

class AdminService
{
    public function __construct(
        private readonly ImageService $imageService,
    ) {}

    public function getAdminsWithRoles()
    {
        return Admin::with('roles')->latest()->paginate(15);
    }

    public function getAllRoles()
    {
        return Role::all();
    }

    /**
     * Create a new admin and assign roles inside a DB transaction.
     */
    public function createAdmin(array $data): Admin
    {
        return DB::transaction(function () use ($data) {
            $adminData = [
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
            ];

            if (isset($data['avatar'])) {
                $this->imageService->compress($data['avatar']);
                $result           = FileUpload::upload($data['avatar'], ['folder_name' => 'admins']);
                $adminData['avatar'] = $result->path;
            }

            $admin = Admin::create($adminData);

            if (!empty($data['roles'])) {
                $admin->syncRoles($data['roles']);
            }

            return $admin;
        });
    }

    /**
     * Update admin data and roles inside a DB transaction.
     */
    public function updateAdmin(Admin $admin, array $data): Admin
    {
        return DB::transaction(function () use ($admin, $data) {
            $updateData = [
                'name'  => $data['name'],
                'email' => $data['email'],
            ];

            if (!empty($data['password'])) {
                $updateData['password'] = Hash::make($data['password']);
            }

            if (isset($data['avatar'])) {
                if ($admin->avatar) {
                    try { FileUpload::delete($admin->avatar); } catch (\Exception) {}
                }
                $this->imageService->compress($data['avatar']);
                $result              = FileUpload::upload($data['avatar'], ['folder_name' => 'admins']);
                $updateData['avatar'] = $result->path;
            }

            $admin->update($updateData);

            $admin->syncRoles($data['roles'] ?? []);

            return $admin;
        });
    }

    public function deleteAdmin(Admin $admin): bool
    {
        if ($admin->id === auth('admin')->id()) {
            throw new \Exception('لا يمكنك حذف حسابك الشخصي.');
        }

        if ($admin->hasRole('Super Admin') && Admin::role('Super Admin')->count() === 1) {
            throw new \Exception('لا يمكن حذف آخر مدير نظام في المنصة.');
        }

        return $admin->delete(); // SoftDelete
    }
}
