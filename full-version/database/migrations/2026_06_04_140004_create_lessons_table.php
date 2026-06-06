<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->json('title'); // Spatie Translatable
            $table->unsignedTinyInteger('number');
            $table->string('youtube_url');
            $table->string('duration')->nullable();
            $table->unsignedInteger('views')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['course_id', 'number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
