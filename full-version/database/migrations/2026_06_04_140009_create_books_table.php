<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->json('title');           // Spatie Translatable
            $table->json('author')->nullable(); // Spatie Translatable
            $table->year('year')->nullable();
            $table->string('category')->index(); // Enum via Cast
            $table->string('cover_image')->nullable();
            $table->string('pdf_path')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
