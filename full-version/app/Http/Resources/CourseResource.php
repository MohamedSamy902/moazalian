<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'title'          => $this->getTranslations('title'),
            'slug'           => $this->slug,
            'description'    => $this->getTranslations('description'),
            'thumbnail'      => $this->thumbnail,
            'total_lessons'  => $this->total_lessons,
            'is_published'   => $this->is_published,
            'lessons_count'  => $this->whenCounted('lessons'),
            'created_at'     => $this->created_at?->toISOString(),
        ];
    }
}
