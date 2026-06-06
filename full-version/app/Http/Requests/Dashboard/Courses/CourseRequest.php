<?php

namespace App\Http\Requests\Dashboard\Courses;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Unified Form Request for Course creation and update.
 */
class CourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $courseId = $this->route('course')?->id;

        return [
            'title.ar'        => 'required|string|max:500',
            'title.en'        => 'nullable|string|max:500',
            'slug'            => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-z0-9\-]+$/',
                Rule::unique('courses', 'slug')->ignore($courseId),
            ],
            'description.ar'  => 'nullable|string|max:5000',
            'description.en'  => 'nullable|string|max:5000',
            'thumbnail'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_published'    => 'nullable|boolean',
            'total_lessons'   => 'nullable|integer|min:0|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'title.ar.required' => 'عنوان الكورس بالعربي مطلوب.',
            'slug.unique'       => 'الرابط المختصر مستخدم بالفعل.',
            'slug.regex'        => 'الرابط المختصر يجب أن يحتوي على أحرف وأرقام وشرطات فقط.',
            'thumbnail.max'     => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت.',
        ];
    }
}
