<?php

namespace App\Http\Requests\Dashboard\Roles;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Unified Form Request for Role creation and update.
 */
class RoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $roleId = $this->route('role')?->id;

        return [
            'name'          => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-zA-Z\s]+$/',
                Rule::unique('roles', 'name')->ignore($roleId),
            ],
            'permissions'   => 'required|array|min:1',
            'permissions.*' => 'string|exists:permissions,name',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'          => 'اسم الدور مطلوب.',
            'name.unique'            => 'اسم الدور مستخدم بالفعل.',
            'name.regex'             => 'اسم الدور يجب أن يحتوي على أحرف إنجليزية ومسافات فقط.',
            'permissions.required'   => 'يجب اختيار صلاحية واحدة على الأقل.',
            'permissions.min'        => 'يجب اختيار صلاحية واحدة على الأقل.',
            'permissions.*.exists'   => 'إحدى الصلاحيات المختارة غير موجودة.',
        ];
    }
}
