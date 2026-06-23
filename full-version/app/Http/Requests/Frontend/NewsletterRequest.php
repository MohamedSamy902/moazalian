<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class NewsletterRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:150'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.email'    => 'البريد الإلكتروني غير صحيح.',
        ];
    }
}
