<?php

namespace App\Models;

use App\Enums\BookCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Book extends Model
{
    use HasTranslations, SoftDeletes;

    protected $guarded = [];
    public $translatable = ['title', 'author'];

    protected $casts = [
        'category'    => BookCategory::class,
        'is_featured' => 'boolean',
    ];

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderByDesc('created_at');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }
}
