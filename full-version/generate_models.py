import os

models_dir = '/home/sharo/Desktop/mo3az3lian/full-version/app/Models'

models = {
    'Admin.php': """<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $guarded = [];
    protected $hidden = ['password', 'remember_token'];
}
""",
    'Setting.php': """<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Setting extends Model
{
    use HasTranslations;

    protected $guarded = [];
    public $translatable = ['value'];
}
""",
    'Course.php': """<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Translatable\HasTranslations;

class Course extends Model
{
    use HasTranslations;

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
""",
    'Lesson.php': """<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Lesson extends Model
{
    use HasTranslations;

    protected $guarded = [];
    public $translatable = ['title'];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function attachments()
    {
        return $this->hasMany(LessonAttachment::class);
    }

    public function comments()
    {
        return $this->hasMany(LessonComment::class)->whereNull('parent_id');
    }
}
""",
    'LessonAttachment.php': """<?php
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
""",
    'LessonComment.php': """<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonComment extends Model
{
    protected $guarded = [];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function parent()
    {
        return $this->belongsTo(LessonComment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(LessonComment::class, 'parent_id');
    }
}
""",
    'Video.php': """<?php
namespace App\Models;

use App\Enums\VideoCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Translatable\HasTranslations;

class Video extends Model
{
    use HasTranslations;

    protected $guarded = [];
    public $translatable = ['title'];

    protected $casts = [
        'category' => VideoCategory::class,
        'published_at' => 'datetime',
    ];

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderByDesc('published_at');
    }
}
""",
    'Article.php': """<?php
namespace App\Models;

use App\Enums\ArticleCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Translatable\HasTranslations;

class Article extends Model
{
    use HasTranslations;

    protected $guarded = [];
    public $translatable = ['title', 'body', 'excerpt'];

    protected $casts = [
        'category' => ArticleCategory::class,
        'published_at' => 'datetime',
    ];

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderByDesc('published_at');
    }
}
""",
    'Book.php': """<?php
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
""",
    'Debate.php': """<?php
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
""",
    'QuickReply.php': """<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class QuickReply extends Model
{
    use HasTranslations;

    protected $guarded = [];
    public $translatable = ['question', 'answer'];
}
""",
    'NewsletterSubscriber.php': """<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriber extends Model
{
    protected $guarded = [];
}
"""
}

for filename, content in models.items():
    filepath = os.path.join(models_dir, filename)
    with open(filepath, 'w') as f:
        f.write(content)

print("Models generated successfully!")
