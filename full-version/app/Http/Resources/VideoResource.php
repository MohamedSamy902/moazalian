<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'title'            => $this->getTranslations('title'),
            'slug'             => $this->slug,
            'video_type'       => $this->video_type,
            'video_url'        => $this->video_url,
            'description'      => $this->getTranslations('description'),
            'thumbnail'        => $this->thumbnail,
            'category'         => $this->category?->value,
            'category_label'   => $this->category?->label(),
            'duration'         => $this->duration,
            'views'            => $this->views,
            'is_published'     => !is_null($this->published_at),
            'published_at'     => $this->published_at?->toISOString(),
            'references_count' => $this->whenCounted('references'),
            'references'       => $this->whenLoaded('references', fn () =>
                $this->references->map(fn ($ref) => [
                    'id'      => $ref->id,
                    'type'    => $ref->type,
                    'title'   => $ref->getTranslations('title'),
                    'content' => $ref->content,
                ])
            ),
            'created_at'       => $this->created_at?->toISOString(),
            'updated_at'       => $this->updated_at?->toISOString(),
        ];
    }
}
