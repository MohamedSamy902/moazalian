<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Site identity
            ['key_name' => 'site_name',     'value' => ['ar' => 'موقع معاذ عليان', 'en' => 'Moaz Alian'], 'type' => 'text'],
            ['key_name' => 'site_slogan',   'value' => ['ar' => 'بصيرة وعلم', 'en' => 'Insight & Knowledge'], 'type' => 'text'],
            ['key_name' => 'site_logo',     'value' => null, 'type' => 'image'],
            ['key_name' => 'site_favicon',  'value' => null, 'type' => 'image'],

            // Contact
            ['key_name' => 'contact_email', 'value' => ['ar' => 'info@moazalian.com', 'en' => 'info@moazalian.com'], 'type' => 'text'],
            ['key_name' => 'contact_phone', 'value' => ['ar' => '', 'en' => ''], 'type' => 'text'],

            // Social Media
            ['key_name' => 'social_youtube',   'value' => ['ar' => '', 'en' => ''], 'type' => 'text'],
            ['key_name' => 'social_twitter',   'value' => ['ar' => '', 'en' => ''], 'type' => 'text'],
            ['key_name' => 'social_facebook',  'value' => ['ar' => '', 'en' => ''], 'type' => 'text'],
            ['key_name' => 'social_instagram', 'value' => ['ar' => '', 'en' => ''], 'type' => 'text'],
            ['key_name' => 'social_telegram',  'value' => ['ar' => '', 'en' => ''], 'type' => 'text'],

            // SEO
            ['key_name' => 'meta_description', 'value' => [
                'ar' => 'موقع الشيخ معاذ عليان - مقارنة الأديان، الرد على الشبهات، المناظرات.',
                'en' => 'Moaz Alian website - Comparative religion, misconceptions responses, debates.',
            ], 'type' => 'text'],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(
                ['key_name' => $setting['key_name']],
                [
                    'value' => json_encode($setting['value']),
                    'type'  => $setting['type'],
                ]
            );
        }

        $this->command->info('✅ Settings seeded successfully.');
    }
}
