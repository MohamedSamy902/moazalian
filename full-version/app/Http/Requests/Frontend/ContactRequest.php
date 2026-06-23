<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'    => ['required', 'string', 'max:100'],
            'email'   => ['required', 'email', 'max:150'],
            'subject' => ['required', 'string', 'max:200'],
            'message' => ['required', 'string', 'min:20', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'الاسم مطلوب.',
            'email.required'   => 'البريد الإلكتروني مطلوب.',
            'email.email'      => 'البريد الإلكتروني غير صحيح.',
            'subject.required' => 'الموضوع مطلوب.',
            'message.required' => 'الرسالة مطلوبة.',
            'message.min'      => 'الرسالة يجب أن تحتوي على 20 حرفاً على الأقل.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name'    => strip_tags($this->name ?? ''),
            'subject' => strip_tags($this->subject ?? ''),
            'message' => strip_tags($this->message ?? ''),
        ]);
    }
}
