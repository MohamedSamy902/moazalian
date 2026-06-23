<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add SEO fields to articles
        Schema::table('articles', function (Blueprint $table) {
            $table->json('seo_title')->nullable()->after('thumbnail');
            $table->json('seo_description')->nullable()->after('seo_title');
            $table->json('seo_keywords')->nullable()->after('seo_description');
        });

        // Add SEO fields to videos
        Schema::table('videos', function (Blueprint $table) {
            $table->json('seo_title')->nullable()->after('thumbnail');
            $table->json('seo_description')->nullable()->after('seo_title');
            $table->json('seo_keywords')->nullable()->after('seo_description');
        });

        // Add SEO fields to books
        Schema::table('books', function (Blueprint $table) {
            $table->json('seo_title')->nullable()->after('cover_image');
            $table->json('seo_description')->nullable()->after('seo_title');
            $table->json('seo_keywords')->nullable()->after('seo_description');
        });

        // Add page-level SEO to sections table for static pages (about, dawah, live, references)
        Schema::table('sections', function (Blueprint $table) {
            $table->string('seo_title')->nullable()->after('type');
            $table->string('seo_description')->nullable()->after('seo_title');
            $table->string('seo_keywords')->nullable()->after('seo_description');
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['seo_title', 'seo_description', 'seo_keywords']);
        });
        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn(['seo_title', 'seo_description', 'seo_keywords']);
        });
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['seo_title', 'seo_description', 'seo_keywords']);
        });
        Schema::table('sections', function (Blueprint $table) {
            $table->dropColumn(['seo_title', 'seo_description', 'seo_keywords']);
        });
    }
};
