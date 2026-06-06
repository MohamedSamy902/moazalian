<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\Translatable\HasTranslations;

class QuickResponse extends Model
{
    use HasTranslations;

    protected $guarded = [];
    public $translatable = ['title', 'content'];

    protected $casts = [
        'published_at' => 'datetime',
        'is_published' => 'boolean',
    ];
}
