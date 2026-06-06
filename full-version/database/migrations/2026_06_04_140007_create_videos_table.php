<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->json('title');           // Spatie Translatable
            $table->string('slug')->unique();
            $table->string('video_type')->default('external'); // 'external' | 'upload'
            $table->string('video_url')->nullable();
            $table->json('description')->nullable(); // Spatie Translatable
            $table->string('thumbnail')->nullable();
            $table->string('category')->nullable()->index();  // Enum via Cast
            $table->string('duration')->nullable();
            $table->unsignedInteger('views')->default(0);
            $table->timestamp('published_at')->nullable()->index();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['category', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
