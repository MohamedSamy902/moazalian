<?php

namespace App\Services\Dashboard;

use App\Models\User;
use App\Services\Core\ImageService;
use Illuminate\Support\Facades\DB;
use MohamedSamy902\AdvancedFileUpload\Facades\FileUpload;

class UserService
{
    public function __construct(
        private readonly ImageService $imageService,
    ) {}

    public function getUsersStats(): array
    {
        return User::toBase()
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) as inactive,
                SUM(CASE WHEN status = 'blocked' THEN 1 ELSE 0 END) as blocked
            ")
            ->first();
    }

    public function getUsers(array $filters = [])
    {
        $query = User::query();

        if (!empty($filters['search'])) {
            $search = mb_substr(strip_tags($filters['search']), 0, 100);
            $query->where(function ($q) use ($search) {
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

    public function createUser(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $userData = [
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => $data['password'], // Hashed via User model cast
                'phone'    => $data['phone'] ?? null,
                'religion' => $data['religion'] ?? null,
                'status'   => $data['status'] ?? 'active',
            ];

            if (isset($data['avatar'])) {
                $this->imageService->compress($data['avatar']);
                $result           = FileUpload::upload($data['avatar'], ['folder_name' => 'users']);
                $userData['avatar'] = $result->path;
            }

            return User::create($userData);
        });
    }

    public function updateUser(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            $updateData = [
                'name'     => $data['name'],
                'email'    => $data['email'],
                'phone'    => $data['phone'] ?? null,
                'religion' => $data['religion'] ?? null,
                'status'   => $data['status'] ?? 'active',
            ];

            if (!empty($data['password'])) {
                $updateData['password'] = $data['password']; // Hashed via cast
            }

            if (isset($data['avatar'])) {
                if ($user->avatar) {
                    try { FileUpload::delete($user->avatar); } catch (\Exception) {}
                }
                $this->imageService->compress($data['avatar']);
                $result              = FileUpload::upload($data['avatar'], ['folder_name' => 'users']);
                $updateData['avatar'] = $result->path;
            }

            $user->update($updateData);

            return $user;
        });
    }

    public function deleteUser(User $user): bool
    {
        return DB::transaction(function () use ($user) {
            if ($user->avatar) {
                try { FileUpload::delete($user->avatar); } catch (\Exception) {}
            }
            return $user->delete(); // SoftDelete
        });
    }
}
