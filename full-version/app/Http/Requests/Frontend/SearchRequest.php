<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates and sanitizes search/filter inputs from the frontend.
 * Prevents XSS and SQL injection via strict validation rules.
 */
class SearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search'   => 'nullable|string|max:100|regex:/^[\p{L}\p{N}\s\-_]+$/u',
            'category' => 'nullable|string|max:50|alpha_dash',
            'page'     => 'nullable|integer|min:1|max:1000',
            'per_page' => 'nullable|integer|min:6|max:48',
        ];
    }

    public function messages(): array
    {
        return [
            'search.max'    => 'نص البحث يجب ألا يتجاوز 100 حرف.',
            'search.regex'  => 'نص البحث يحتوي على رموز غير مسموح بها.',
            'category.max'  => 'التصنيف المدخل غير صالح.',
        ];
    }
}
