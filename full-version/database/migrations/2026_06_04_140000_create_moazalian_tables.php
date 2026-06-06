<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Admins
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // Settings (JSON for translatable value)
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key_name')->unique();
            $table->json('value')->nullable();
            $table->enum('type', ['text', 'image', 'boolean'])->default('text');
            $table->timestamps();
        });

        // Courses
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->json('title'); // Spatie translatable
            $table->string('slug')->unique();
            $table->json('description')->nullable(); // Spatie translatable
            $table->string('thumbnail')->nullable();
            $table->unsignedTinyInteger('total_lessons')->default(0);
            $table->boolean('is_published')->default(false)->index();
            $table->timestamps();
        });

        // Lessons
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->json('title'); // Translatable
            $table->unsignedTinyInteger('number');
            $table->string('youtube_url');
            $table->string('duration')->nullable();
            $table->unsignedInteger('views')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            
            $table->index(['course_id', 'number']);
        });

        // Lesson Attachments
        Schema::create('lesson_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained('lessons')->cascadeOnDelete();
            $table->json('name'); // Translatable
            $table->string('file_path');
            $table->string('file_size')->nullable();
            $table->timestamps();
        });

        // Lesson Comments
        Schema::create('lesson_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained('lessons')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('lesson_comments')->cascadeOnDelete();
            $table->string('user_name');
            $table->text('body');
            $table->timestamps();
        });

        // Videos
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->json('title'); // Translatable
            $table->string('slug')->unique();
            $table->string('youtube_url');
            $table->string('thumbnail')->nullable();
            $table->string('category')->index(); // Enum via Casts
            $table->string('duration')->nullable();
            $table->unsignedInteger('views')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        // Articles
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->json('title'); // Translatable
            $table->string('slug')->unique();
            $table->json('body'); // Translatable
            $table->json('excerpt')->nullable(); // Translatable
            $table->string('category'); // Enum
            $table->unsignedTinyInteger('read_time')->default(5);
            $table->string('thumbnail')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['category', 'published_at']);
        });

        // Books
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->json('title'); // Translatable
            $table->json('author')->nullable(); // Translatable
            $table->year('year')->nullable();
            $table->string('category'); // Enum
            $table->string('cover_image')->nullable();
            $table->string('pdf_path')->nullable();
            $table->timestamps();
        });

        // Debates
        Schema::create('debates', function (Blueprint $table) {
            $table->id();
            $table->json('title'); // Translatable
            $table->json('description')->nullable(); // Translatable
            $table->string('youtube_url');
            $table->unsignedInteger('views')->default(0);
            $table->string('duration')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->timestamps();
        });

        // Quick Replies
        Schema::create('quick_replies', function (Blueprint $table) {
            $table->id();
            $table->json('question'); // Translatable
            $table->json('answer'); // Translatable
            $table->unsignedTinyInteger('read_time')->default(3);
            $table->timestamps();
        });

        // Newsletter Subscribers
        Schema::create('newsletter_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->timestamps();
        });
        
        // Add avatar to users
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('avatar');
        });
        Schema::dropIfExists('newsletter_subscribers');
        Schema::dropIfExists('quick_replies');
        Schema::dropIfExists('debates');
        Schema::dropIfExists('books');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('videos');
        Schema::dropIfExists('lesson_comments');
        Schema::dropIfExists('lesson_attachments');
        Schema::dropIfExists('lessons');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('admins');
    }
};
