<?php

namespace App\Models;

use App\Enums\VideoCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Video extends Model
{
    use HasTranslations, SoftDeletes;

    protected $guarded = [];

    /** Translatable content + SEO fields */
    public $translatable = ['title', 'description', 'seo_title', 'seo_description', 'seo_keywords'];

    protected $casts = [
        'category'     => VideoCategory::class,
        'published_at' => 'datetime',
    ];

    /** Convenience accessor \u2014 used in dashboard views */
    public function getIsPublishedAttribute(): bool
    {
        return $this->published_at !== null;
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderByDesc('published_at');
    }

    public function references()
    {
        return $this->hasMany(VideoReference::class);
    }
}
