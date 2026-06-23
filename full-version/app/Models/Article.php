<?php

namespace App\Models;

use App\Enums\ArticleCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Article extends Model
{
    use HasTranslations, SoftDeletes;

    protected $guarded = [];

    /** Translatable content fields + SEO fields */
    public $translatable = ['title', 'body', 'excerpt', 'seo_title', 'seo_description', 'seo_keywords'];

    protected $casts = [
        'category'     => ArticleCategory::class,
        'published_at' => 'datetime',
    ];

    /** Convenience accessor — consistent with VideoService usage */
    public function getIsPublishedAttribute(): bool
    {
        return $this->published_at !== null;
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderByDesc('published_at');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at');
    }
}
