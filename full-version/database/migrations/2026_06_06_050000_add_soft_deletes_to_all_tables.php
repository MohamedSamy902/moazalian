<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * This migration adds soft_deletes columns to all existing tables
 * that require SoftDeletes support. This is an additive migration
 * that preserves all existing data.
 */
return new class extends Migration
{
    /**
     * Tables that need softDeletes added.
     */
    private array $tables = [
        'users',
        'admins',
        'courses',
        'lessons',
        'videos',
        'articles',
        'books',
        'debates',
        'quick_replies',
        'quick_responses',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'deleted_at')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->softDeletes();
                });
            }
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'deleted_at')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->dropSoftDeletes();
                });
            }
        }
    }
};
