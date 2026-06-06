<?php

namespace App\Services\Dashboard;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use MohamedSamy902\AdvancedFileUpload\Facades\FileUpload;

class UserService
{
    public function getUsersStats()
    {
        return [
            'total' => User::count(),
            'active' => User::where('status', 'active')->count(),
            'inactive' => User::where('status', 'inactive')->count(),
            'blocked' => User::where('status', 'blocked')->count(),
        ];
    }

    public function getUsers(array $filters = [])
    {
        $query = User::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('religion', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('id', 'desc')->paginate(10)->withQueryString();
    }

    public function createUser(array $data)
    {
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'religion' => $data['religion'] ?? null,
            'status' => $data['status'] ?? 'active',
        ];

        if (isset($data['avatar'])) {
            $result = FileUpload::upload($data['avatar'], ['folder_name' => 'users']);
            $userData['avatar'] = $result->path;
        }

        return User::create($userData);
    }

    public function updateUser(User $user, array $data)
    {
        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'religion' => $data['religion'] ?? null,
            'status' => $data['status'] ?? 'active',
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        if (isset($data['avatar'])) {
            if ($user->avatar) {
                try {
                    FileUpload::delete($user->avatar);
                } catch (\Exception $e) {}
            }
            $result = FileUpload::upload($data['avatar'], ['folder_name' => 'users']);
            $updateData['avatar'] = $result->path;
        }

        $user->update($updateData);

        return $user;
    }

    public function deleteUser(User $user)
    {
        if ($user->avatar) {
            try {
                FileUpload::delete($user->avatar);
            } catch (\Exception $e) {}
        }
        return $user->delete();
    }
}
