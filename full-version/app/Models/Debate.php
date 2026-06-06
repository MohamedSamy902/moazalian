<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Translatable\HasTranslations;

class Debate extends Model
{
    use HasTranslations;

    protected $guarded = [];
    public $translatable = ['title', 'description'];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }
}
