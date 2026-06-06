<?php

namespace Database\Seeders;

use App\Models\Video;
use Illuminate\Database\Seeder;

class VideosSeeder extends Seeder
{
    public function run(): void
    {
        $videos = [
            [
                'title'        => ['ar' => 'مناظرة كبرى حول المخطوطات', 'en' => 'Major Debate on Manuscripts'],
                'slug'         => 'major-debate-on-manuscripts',
                'video_type'   => 'external',
                'video_url'    => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'category'     => 'debate',
                'views'        => 15000,
                'published_at' => now()->subDays(10),
            ],
            [
                'title'        => ['ar' => 'نقد كتابي: مناقشة المخطوطات القديمة', 'en' => 'Book Critique: Ancient Manuscripts'],
                'slug'         => 'book-critique-ancient-manuscripts',
                'video_type'   => 'external',
                'video_url'    => 'https://www.youtube.com/watch?v=example2',
                'category'     => 'critique',
                'views'        => 8500,
                'published_at' => now()->subDays(5),
            ],
            [
                'title'        => ['ar' => 'حوار مباشر مع باحث مسيحي', 'en' => 'Direct Dialogue with a Christian Scholar'],
                'slug'         => 'direct-dialogue-christian-scholar',
                'video_type'   => 'external',
                'video_url'    => 'https://www.youtube.com/watch?v=example3',
                'category'     => 'direct_dialogue',
                'views'        => 22000,
                'published_at' => now()->subDays(20),
            ],
            [
                'title'        => ['ar' => 'محاضرة في علم مقارنة الأديان', 'en' => 'Lecture on Comparative Religion Science'],
                'slug'         => 'lecture-comparative-religion-science',
                'video_type'   => 'external',
                'video_url'    => 'https://www.youtube.com/watch?v=example4',
                'category'     => 'lecture',
                'views'        => 5200,
                'published_at' => now()->subDays(3),
            ],
        ];


        foreach ($videos as $videoData) {
            Video::firstOrCreate(['slug' => $videoData['slug']], $videoData);
        }

        $this->command->info('✅ Videos seeded successfully.');
    }
}
