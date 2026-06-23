<?php

namespace App\Http\Requests\Dashboard\Videos;

use App\Enums\VideoCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Unified Form Request for Video creation and update.
 * Uses HTTP method to differentiate Store vs Update rules.
 */
class VideoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isStore = $this->isMethod('POST');

        return [
            'title.ar'             => 'required|string|max:500',
            'title.en'             => 'nullable|string|max:500',
            'video_type'           => 'required|in:external,upload',
            'video_url'            => [
                'nullable',
                'url',
                Rule::requiredIf(fn () => $this->input('video_type') === 'external'),
            ],
            'video_file'           => [
                'nullable',
                'file',
                'mimes:mp4,mov,ogg,qt',
                'max:204800', // 200 MB
                Rule::requiredIf(fn () => $isStore && $this->input('video_type') === 'upload'),
            ],
            'thumbnail'            => 'nullable|image|max:5120',
            'description.ar'       => 'nullable|string|max:5000',
            'description.en'       => 'nullable|string|max:5000',
            'category'             => ['nullable', Rule::enum(VideoCategory::class)],
            'duration'             => 'nullable|string|max:20',
            'is_published'         => 'nullable|boolean',
            'references'           => 'nullable|array|max:20',
            'references.*.id'      => 'nullable|integer|exists:video_references,id',
            'references.*.title.ar' => 'nullable|string|max:255',
            'references.*.title.en' => 'nullable|string|max:255',
            'references.*.type'    => 'required_with:references|in:upload,link,text',
            'references.*.content' => 'nullable|string|max:2000',
            'references.*.file'    => 'nullable|file|mimes:pdf,doc,docx,mp4,mov,ogg,qt|max:204800',
            // SEO fields
            'seo_title.ar'         => 'nullable|string|max:255',
            'seo_title.en'         => 'nullable|string|max:255',
            'seo_description.ar'   => 'nullable|string|max:500',
            'seo_description.en'   => 'nullable|string|max:500',
            'seo_keywords.ar'      => 'nullable|string|max:500',
            'seo_keywords.en'      => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'title.ar.required'     => 'العنوان بالعربي مطلوب.',
            'video_type.required'   => 'نوع الفيديو مطلوب.',
            'video_type.in'         => 'نوع الفيديو يجب أن يكون (رابط خارجي) أو (رفع ملف).',
            'video_url.url'         => 'رابط الفيديو غير صالح.',
            'video_url.required_if' => 'رابط الفيديو مطلوب عند اختيار (رابط خارجي).',
            'video_file.required_if' => 'ملف الفيديو مطلوب عند اختيار (رفع ملف).',
            'video_file.max'        => 'حجم ملف الفيديو يجب ألا يتجاوز 200 ميجابايت.',
            'category.enum'         => 'التصنيف المختار غير صالح.',
        ];
    }
}
