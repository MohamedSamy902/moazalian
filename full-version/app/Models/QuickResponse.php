<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class QuickResponse extends Model
{
    use HasTranslations, SoftDeletes;

    protected $guarded = [];
    public $translatable = ['title', 'content'];

    protected $casts = [
        'published_at' => 'datetime',
        'is_published' => 'boolean',
    ];
}
