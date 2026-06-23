<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First convert empty strings to a default to avoid NULL constraint
        DB::statement("UPDATE videos SET category = 'debate' WHERE category = '' OR category IS NULL");

        Schema::table('videos', function (Blueprint $table) {
            $table->string('category')->nullable()->default(null)->change();
        });

        // Now we can safely set them to NULL
        DB::statement("UPDATE videos SET category = NULL WHERE category = 'debate' AND slug LIKE 'yt-%'");
    }

    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->string('category')->nullable(false)->default('debate')->change();
        });
    }
};
