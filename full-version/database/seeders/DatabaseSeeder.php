<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Article;
use App\Models\Course;
use App\Models\Video;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles and Admin
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'admin']);
        
        $admin = Admin::firstOrCreate(
            ['email' => 'admin@moazalian.com'],
            [
                'name' => 'Moaz Alian',
                'password' => Hash::make('password')
            ]
        );
        $admin->assignRole($superAdminRole);

        // 2. Courses
        Course::firstOrCreate(
            ['slug' => 'comparative-religion-101'],
            [
                'title' => [
                    'ar' => 'مقدمة في مقارنة الأديان',
                    'en' => 'Introduction to Comparative Religion'
                ],
                'description' => [
                    'ar' => 'دورة شاملة للتعريف بأساسيات مقارنة الأديان.',
                    'en' => 'A comprehensive course introducing the basics of comparative religion.'
                ],
                'is_published' => true,
                'total_lessons' => 10,
            ]
        );

        // 3. Articles
        Article::firstOrCreate(
            ['slug' => 'understanding-manuscripts'],
            [
                'title' => [
                    'ar' => 'فهم المخطوطات القديمة',
                    'en' => 'Understanding Ancient Manuscripts'
                ],
                'body' => [
                    'ar' => 'تفاصيل عن كيفية فحص وتحليل المخطوطات...',
                    'en' => 'Details on how to examine and analyze manuscripts...'
                ],
                'excerpt' => [
                    'ar' => 'مقدمة سريعة في علم المخطوطات.',
                    'en' => 'A quick intro to manuscript science.'
                ],
                'category' => 'history',
                'read_time' => 5,
                'published_at' => now(),
            ]
        );

        // 4. Videos
        Video::firstOrCreate(
            ['slug' => 'debate-with-guest-1'],
            [
                'title' => [
                    'ar' => 'مناظرة كبرى حول المخطوطات',
                    'en' => 'Major Debate on Manuscripts'
                ],
                'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'category' => 'debate',
                'views' => 15000,
                'published_at' => now(),
            ]
        );
    }
}
