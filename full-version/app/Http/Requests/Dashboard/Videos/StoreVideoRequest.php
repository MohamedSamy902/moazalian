<?php

namespace App\Http\Requests\Dashboard\Videos;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreVideoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title.ar' => 'required|string',
            'title.en' => 'nullable|string',
            'video_type' => 'required|in:external,upload',
            'video_url' => 'nullable|url',
            'video_file' => 'nullable|file|mimes:mp4,mov,ogg,qt|max:100000',
            'description.ar' => 'nullable|string',
            'description.en' => 'nullable|string',
            'category' => ['nullable', \Illuminate\Validation\Rule::enum(\App\Enums\VideoCategory::class)],
            'duration' => 'nullable|string',
            'is_published' => 'nullable|boolean',
            'references' => 'nullable|array',
            'references.*.id' => 'nullable|integer',
            'references.*.title.ar' => 'nullable|string',
            'references.*.title.en' => 'nullable|string',
            'references.*.type' => 'required_with:references|in:upload,link,text',
            'references.*.content' => 'nullable|string',
            'references.*.file' => 'nullable|file|mimes:mp4,mov,ogg,qt|max:100000',
        ];
    }
}
