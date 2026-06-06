<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // 1. Roles, Permissions & Default Admin (must run first)
            AdminAndRolesSeeder::class,

            // 2. Core Settings
            SettingsSeeder::class,

            // 3. Homepage Sections (existing seeder)
            SectionsSeeder::class,

            // 4. Content
            CoursesSeeder::class,
            VideosSeeder::class,
            QuickResponsesSeeder::class,
        ]);

        $this->command->info('🎉 All seeders completed successfully!');
    }
}
