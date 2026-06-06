<?php

namespace App\Http\Requests\Dashboard\Admins;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Unified Form Request for Admin creation and update.
 */
class AdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isStore  = $this->isMethod('POST');
        $adminId  = $this->route('admin')?->id;

        return [
            'name'     => 'required|string|max:255',
            'email'    => [
                'required',
                'email',
                Rule::unique('admins', 'email')->ignore($adminId),
            ],
            'password' => [
                $isStore ? 'required' : 'nullable',
                'string',
                'min:8',
            ],
            'roles'    => 'nullable|array',
            'roles.*'  => 'string|exists:roles,name',
            'avatar'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'          => 'الاسم مطلوب.',
            'email.required'         => 'البريد الإلكتروني مطلوب.',
            'email.email'            => 'البريد الإلكتروني غير صالح.',
            'email.unique'           => 'البريد الإلكتروني مستخدم بالفعل.',
            'password.required'      => 'كلمة المرور مطلوبة.',
            'password.min'           => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل.',
            'avatar.image'           => 'الملف يجب أن يكون صورة.',
            'avatar.max'             => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت.',
        ];
    }
}
