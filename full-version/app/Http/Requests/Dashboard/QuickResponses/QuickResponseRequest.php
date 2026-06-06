<?php

namespace App\Http\Requests\Dashboard\QuickResponses;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Unified Form Request for QuickResponse creation and update.
 */
class QuickResponseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title.ar'        => 'nullable|string|max:500',
            'title.en'        => 'nullable|string|max:500',
            'type'            => 'required|in:text,image,video',
            'content_text.ar' => [
                'nullable',
                'string',
                'max:5000',
                'required_if:type,text',
            ],
            'content_text.en' => 'nullable|string|max:5000',
            'youtube_url'     => [
                'nullable',
                'url',
                'max:500',
                'required_if:type,video',
            ],
            'image'           => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:2048',
                'required_if:type,image',
            ],
            'is_published'    => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'type.required'            => 'نوع الرد السريع مطلوب.',
            'type.in'                  => 'نوع الرد يجب أن يكون: نص أو صورة أو فيديو.',
            'content_text.ar.required_if' => 'المحتوى النصي مطلوب عند اختيار نوع (نص).',
            'youtube_url.required_if'  => 'رابط اليوتيوب مطلوب عند اختيار نوع (فيديو).',
            'youtube_url.url'          => 'رابط اليوتيوب غير صالح.',
            'image.required_if'        => 'الصورة مطلوبة عند اختيار نوع (صورة).',
            'image.max'                => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت.',
        ];
    }
}
