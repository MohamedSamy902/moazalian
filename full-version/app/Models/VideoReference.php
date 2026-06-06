<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class VideoReference extends Model
{
    use HasTranslations;

    protected $fillable = [
        'video_id',
        'type',
        'title',
        'content',
    ];

    public $translatable = ['title'];

    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
