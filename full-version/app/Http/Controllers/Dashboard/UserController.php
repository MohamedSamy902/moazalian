<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Dashboard\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService
    ) {}

    private function checkPermission($permission)
    {
        /** @var \App\Models\Admin $user */
        $user = auth('admin')->user();
        abort_if(!$user->hasRole('Super Admin') && !$user->can($permission), 403, 'غير مصرح لك');
    }

    public function index(Request $request)
    {
        $this->checkPermission('users.view');
        $filters = $request->only(['search', 'status']);
        $users = $this->userService->getUsers($filters);
        $stats = $this->userService->getUsersStats();
        return view('dashboard.users.index', compact('users', 'stats'));
    }

    public function create()
    {
        $this->checkPermission('users.create');
        $user = null;
        return view('dashboard.users.form-modal', compact('user'));
    }

    public function store(Request $request)
    {
        $this->checkPermission('users.create');
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:20',
            'religion' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive,blocked',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $this->userService->createUser($request->all());

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة المشترك بنجاح'
        ]);
    }

    public function edit(User $user)
    {
        $this->checkPermission('users.edit');
        return view('dashboard.users.form-modal', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->checkPermission('users.edit');

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'phone' => 'nullable|string|max:20',
            'religion' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive,blocked',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $this->userService->updateUser($user, $request->all());

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث بيانات المشترك بنجاح'
        ]);
    }

    public function destroy(User $user)
    {
        $this->checkPermission('users.delete');
        try {
            $this->userService->deleteUser($user);
            return response()->json([
                'success' => true,
                'message' => 'تم حذف المشترك بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
