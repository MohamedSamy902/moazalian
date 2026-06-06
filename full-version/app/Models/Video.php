<?php
namespace App\Models;

use App\Enums\VideoCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Translatable\HasTranslations;

class Video extends Model
{
    use HasTranslations;

    protected $guarded = [];
    public $translatable = ['title', 'description'];

    protected $casts = [
        'category' => VideoCategory::class,
        'published_at' => 'datetime',
    ];

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderByDesc('published_at');
    }

    public function references()
    {
        return $this->hasMany(VideoReference::class);
    }
}
