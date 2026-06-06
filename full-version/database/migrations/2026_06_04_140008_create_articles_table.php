<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->json('title');           // Spatie Translatable
            $table->string('slug')->unique();
            $table->json('body');            // Spatie Translatable
            $table->json('excerpt')->nullable(); // Spatie Translatable
            $table->string('category')->index(); // Enum via Cast
            $table->unsignedTinyInteger('read_time')->default(5);
            $table->string('thumbnail')->nullable();
            $table->timestamp('published_at')->nullable()->index();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['category', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
