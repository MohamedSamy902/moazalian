<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quick_responses', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable(); // Translatable title (optional, sometimes it's just a quick reply)
            $table->enum('type', ['text', 'video', 'image'])->default('text');
            $table->json('content')->nullable(); // Translatable text content
            $table->string('attachment')->nullable(); // Image path or Video URL
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quick_responses');
    }
};
