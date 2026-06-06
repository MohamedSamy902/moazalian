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
    public $translatable = ['title', 'body', 'excerpt'];

    protected $casts = [
        'category'     => ArticleCategory::class,
        'published_at' => 'datetime',
    ];

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderByDesc('published_at');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at');
    }
}
