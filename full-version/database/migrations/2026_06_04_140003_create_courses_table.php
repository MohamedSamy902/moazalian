<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->json('title');           // Spatie Translatable
            $table->string('slug')->unique()->index();
            $table->json('description')->nullable(); // Spatie Translatable
            $table->string('thumbnail')->nullable();
            $table->unsignedTinyInteger('total_lessons')->default(0);
            $table->boolean('is_published')->default(false)->index();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
