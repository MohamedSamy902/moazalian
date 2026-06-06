<?php
namespace App\Models;

use App\Enums\BookCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Translatable\HasTranslations;

class Book extends Model
{
    use HasTranslations;

    protected $guarded = [];
    public $translatable = ['title', 'author'];

    protected $casts = [
        'category' => BookCategory::class,
    ];

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderByDesc('created_at');
    }
}
