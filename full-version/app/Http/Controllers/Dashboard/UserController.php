<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Users\UserRequest;
use App\Models\User;
use App\Services\Dashboard\UserService;
use App\Traits\ApiResponse;
use App\Traits\ChecksPermissions;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ChecksPermissions, ApiResponse;

    public function __construct(
        private readonly UserService $userService
    ) {}

    public function index(Request $request)
    {
        $this->checkPermission('users.view');
        $filters = $request->only(['search', 'status']);
        $users   = $this->userService->getUsers($filters);
        $stats   = $this->userService->getUsersStats();
        return view('dashboard.users.index', compact('users', 'stats'));
    }

    public function create()
    {
        $this->checkPermission('users.create');
        $user = null;
        return view('dashboard.users.form-modal', compact('user'));
    }

    public function store(UserRequest $request)
    {
        $this->checkPermission('users.create');

        $this->userService->createUser($request->validated());

        return $this->successResponse('تم إضافة المشترك بنجاح');
    }

    public function edit(User $user)
    {
        $this->checkPermission('users.edit');
        return view('dashboard.users.form-modal', compact('user'));
    }

    public function update(UserRequest $request, User $user)
    {
        $this->checkPermission('users.edit');

        $this->userService->updateUser($user, $request->validated());

        return $this->successResponse('تم تحديث بيانات المشترك بنجاح');
    }

    public function destroy(User $user)
    {
        $this->checkPermission('users.delete');

        try {
            $this->userService->deleteUser($user);
            return $this->successResponse('تم حذف المشترك بنجاح');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
