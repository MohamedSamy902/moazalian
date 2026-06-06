<?php

namespace App\Http\Requests\Dashboard\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Unified Form Request for User creation and update.
 */
class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isStore = $this->isMethod('POST');
        $userId  = $this->route('user')?->id;

        return [
            'name'     => 'required|string|max:255',
            'email'    => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'password' => [
                $isStore ? 'required' : 'nullable',
                'string',
                'min:8',
            ],
            'phone'    => 'nullable|string|max:20|regex:/^[+\d\s\-()]+$/',
            'religion' => 'nullable|string|max:100',
            'status'   => 'required|in:active,inactive,blocked',
            'avatar'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'الاسم مطلوب.',
            'email.required'    => 'البريد الإلكتروني مطلوب.',
            'email.email'       => 'البريد الإلكتروني غير صالح.',
            'email.unique'      => 'البريد الإلكتروني مستخدم بالفعل.',
            'password.required' => 'كلمة المرور مطلوبة.',
            'password.min'      => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل.',
            'phone.regex'       => 'رقم الهاتف غير صالح.',
            'status.required'   => 'حالة المشترك مطلوبة.',
            'status.in'         => 'حالة المشترك يجب أن تكون: نشط، غير نشط، أو محظور.',
            'avatar.max'        => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت.',
        ];
    }
}
