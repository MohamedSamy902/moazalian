<?php

use Illuminate\Database\Migrations\Migration;

/**
 * DEPRECATED — REFACTORED
 *
 * This monolithic migration has been split into individual files:
 *   2026_06_04_140001_create_admins_table.php
 *   2026_06_04_140002_create_settings_table.php
 *   2026_06_04_140003_create_courses_table.php
 *   2026_06_04_140004_create_lessons_table.php
 *   2026_06_04_140005_create_lesson_attachments_table.php
 *   2026_06_04_140006_create_lesson_comments_table.php
 *   2026_06_04_140007_create_videos_table.php
 *   2026_06_04_140008_create_articles_table.php
 *   2026_06_04_140009_create_books_table.php
 *   2026_06_04_140010_create_debates_table.php
 *   2026_06_04_140011_create_quick_replies_table.php
 *   2026_06_04_140012_create_newsletter_subscribers_table.php
 *
 * This stub is kept to preserve migration history.
 * Since the DB is already set up, this stub does nothing.
 */
return new class extends Migration
{
    public function up(): void
    {
        // No-op: all tables are created in individual migration files above.
    }

    public function down(): void
    {
        // No-op: dropping is handled in individual migration files above.
    }
};
