<?php

namespace App\Http\Requests\Dashboard\QuickResponses;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateQuickResponseRequest extends FormRequest
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
            'type' => 'required|in:text,image,video',
            'content_text.ar' => 'nullable|string|required_if:type,text',
            'content_text.en' => 'nullable|string',
            'youtube_url' => 'nullable|url|required_if:type,video',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048|required_if:type,image',
            'is_published' => 'nullable|boolean',
        ];
    }
}
