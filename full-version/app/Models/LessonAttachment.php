<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class LessonAttachment extends Model
{
    use HasTranslations;

    protected $guarded = [];
    public $translatable = ['name'];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
