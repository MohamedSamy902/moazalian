<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Course extends Model
{
    use HasTranslations, SoftDeletes;

    protected $guarded = [];
    public $translatable = ['title', 'description'];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }
}
