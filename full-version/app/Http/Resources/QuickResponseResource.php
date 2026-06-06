<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuickResponseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'title'        => $this->getTranslations('title'),
            'type'         => $this->type,
            'content'      => $this->getTranslations('content'),
            'attachment'   => $this->attachment,
            'is_published' => $this->is_published,
            'published_at' => $this->published_at?->toISOString(),
            'created_at'   => $this->created_at?->toISOString(),
        ];
    }
}
