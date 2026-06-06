<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('debates', function (Blueprint $table) {
            $table->id();
            $table->json('title');           // Spatie Translatable
            $table->json('description')->nullable(); // Spatie Translatable
            $table->string('youtube_url');
            $table->unsignedInteger('views')->default(0);
            $table->string('duration')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('debates');
    }
};
