<?php

namespace Database\Seeders;

use App\Models\QuickResponse;
use Illuminate\Database\Seeder;

class QuickResponsesSeeder extends Seeder
{
    public function run(): void
    {
        $responses = [
            [
                'title'        => ['ar' => 'من هو معاذ عليان؟', 'en' => 'Who is Moaz Alian?'],
                'type'         => 'text',
                'content'      => [
                    'ar' => 'معاذ عليان باحث إسلامي متخصص في مقارنة الأديان والرد على الشبهات.',
                    'en' => 'Moaz Alian is an Islamic researcher specializing in comparative religion and responding to misconceptions.',
                ],
                'is_published' => true,
                'published_at' => now(),
            ],
            [
                'title'        => ['ar' => 'كيف أتواصل مع الشيخ معاذ؟', 'en' => 'How to contact Sheikh Moaz?'],
                'type'         => 'text',
                'content'      => [
                    'ar' => 'يمكنك التواصل من خلال حساباته على منصات التواصل الاجتماعي أو عبر نموذج التواصل في الموقع.',
                    'en' => 'You can reach him through his social media accounts or via the contact form on the website.',
                ],
                'is_published' => true,
                'published_at' => now(),
            ],
            [
                'title'        => ['ar' => 'هل تتاح المناظرات للمشاهدة مجاناً؟', 'en' => 'Are debates available for free?'],
                'type'         => 'text',
                'content'      => [
                    'ar' => 'نعم، جميع المناظرات والمحاضرات متاحة مجاناً على الموقع وقناة اليوتيوب.',
                    'en' => 'Yes, all debates and lectures are available for free on the website and YouTube channel.',
                ],
                'is_published' => true,
                'published_at' => now(),
            ],
        ];

        foreach ($responses as $data) {
            QuickResponse::firstOrCreate(
                ['title->ar' => $data['title']['ar']],
                $data
            );
        }

        $this->command->info('✅ Quick Responses seeded successfully.');
    }
}
