<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Database\Seeder;

class CoursesSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            [
                'title'         => ['ar' => 'مقدمة في مقارنة الأديان', 'en' => 'Introduction to Comparative Religion'],
                'slug'          => 'comparative-religion-101',
                'description'   => [
                    'ar' => 'دورة شاملة للتعريف بأساسيات مقارنة الأديان وأهم المذاهب.',
                    'en' => 'A comprehensive course introducing the basics of comparative religion and major doctrines.',
                ],
                'is_published'  => true,
                'total_lessons' => 5,
            ],
            [
                'title'         => ['ar' => 'الرد على الشبهات المسيحية', 'en' => 'Responding to Christian Misconceptions'],
                'slug'          => 'responding-to-christian-misconceptions',
                'description'   => [
                    'ar' => 'دورة متخصصة في الرد على الشبهات المثارة حول الإسلام من المنظور المسيحي.',
                    'en' => 'A specialized course in responding to misconceptions about Islam from a Christian perspective.',
                ],
                'is_published'  => true,
                'total_lessons' => 8,
            ],
        ];

        foreach ($courses as $courseData) {
            Course::firstOrCreate(['slug' => $courseData['slug']], $courseData);
        }

        $this->command->info('✅ Courses seeded successfully.');
    }
}
