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
        Schema::table('videos', function (Blueprint $table) {
            $table->renameColumn('youtube_url', 'video_url');
        });
        
        Schema::table('videos', function (Blueprint $table) {
            $table->string('video_url')->nullable()->change();
            $table->enum('video_type', ['external', 'upload'])->default('external')->after('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn('video_type');
        });
        
        Schema::table('videos', function (Blueprint $table) {
            $table->renameColumn('video_url', 'youtube_url');
        });
    }
};
