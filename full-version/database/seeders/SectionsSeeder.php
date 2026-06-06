<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SectionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            // Hero Section
            ['page' => 'home', 'section_name' => 'hero', 'key' => 'hero_title', 'value' => ['ar' => 'باحث ومناظر', 'en' => 'Researcher & Debater'], 'type' => 'text'],
            ['page' => 'home', 'section_name' => 'hero', 'key' => 'hero_subtitle', 'value' => ['ar' => 'مقارنة الأديان', 'en' => 'Comparative Religion'], 'type' => 'text'],
            ['page' => 'home', 'section_name' => 'hero', 'key' => 'hero_description', 'value' => ['ar' => 'أهلاً بك في الموقع الرسمي للباحث والمناظر معاذ عليان. نقدم لك دراسات موثقة، ردوداً أكاديمية، ومناظرات مباشرة في الحوار الإسلامي المسيحي ونقد الكتاب المقدس.', 'en' => 'Welcome to the official website of Moaz Alian. We provide documented studies, academic responses, and live debates.'], 'type' => 'textarea'],
            ['page' => 'home', 'section_name' => 'hero', 'key' => 'hero_btn1_text', 'value' => ['ar' => 'ابدأ المشاهدة', 'en' => 'Start Watching'], 'type' => 'text'],
            ['page' => 'home', 'section_name' => 'hero', 'key' => 'hero_btn1_url', 'value' => ['ar' => 'videos.html', 'en' => 'videos.html'], 'type' => 'url'],
            ['page' => 'home', 'section_name' => 'hero', 'key' => 'hero_btn2_text', 'value' => ['ar' => 'تعرف على معاذ', 'en' => 'About Moaz'], 'type' => 'text'],
            ['page' => 'home', 'section_name' => 'hero', 'key' => 'hero_btn2_url', 'value' => ['ar' => '#about', 'en' => '#about'], 'type' => 'url'],
            ['page' => 'home', 'section_name' => 'hero', 'key' => 'hero_image', 'value' => ['ar' => 'assets/moaz.jpg', 'en' => 'assets/moaz.jpg'], 'type' => 'image'],
            
            // Hero Stats
            ['page' => 'home', 'section_name' => 'hero_stats', 'key' => 'hero_stat1_number', 'value' => ['ar' => '1', 'en' => '1'], 'type' => 'text'],
            ['page' => 'home', 'section_name' => 'hero_stats', 'key' => 'hero_stat1_label', 'value' => ['ar' => 'مشترك نشط', 'en' => 'Active Subscriber'], 'type' => 'text'],
            ['page' => 'home', 'section_name' => 'hero_stats', 'key' => 'hero_stat1_suffix', 'value' => ['ar' => 'M+', 'en' => 'M+'], 'type' => 'text'],
            ['page' => 'home', 'section_name' => 'hero_stats', 'key' => 'hero_stat2_number', 'value' => ['ar' => '120', 'en' => '120'], 'type' => 'text'],
            ['page' => 'home', 'section_name' => 'hero_stats', 'key' => 'hero_stat2_label', 'value' => ['ar' => 'مناظرة مباشرة', 'en' => 'Live Debate'], 'type' => 'text'],
            ['page' => 'home', 'section_name' => 'hero_stats', 'key' => 'hero_stat2_suffix', 'value' => ['ar' => '+', 'en' => '+'], 'type' => 'text'],
            ['page' => 'home', 'section_name' => 'hero_stats', 'key' => 'hero_stat3_number', 'value' => ['ar' => '98', 'en' => '98'], 'type' => 'text'],
            ['page' => 'home', 'section_name' => 'hero_stats', 'key' => 'hero_stat3_label', 'value' => ['ar' => 'تفاعل إيجابي', 'en' => 'Positive Interaction'], 'type' => 'text'],
            ['page' => 'home', 'section_name' => 'hero_stats', 'key' => 'hero_stat3_suffix', 'value' => ['ar' => '%', 'en' => '%'], 'type' => 'text'],

            // Socials
            ['page' => 'home', 'section_name' => 'socials', 'key' => 'social_youtube', 'value' => ['ar' => 'https://www.youtube.com/c/moazalian', 'en' => 'https://www.youtube.com/c/moazalian'], 'type' => 'url'],
            ['page' => 'home', 'section_name' => 'socials', 'key' => 'social_facebook', 'value' => ['ar' => 'https://www.facebook.com/moazalian', 'en' => 'https://www.facebook.com/moazalian'], 'type' => 'url'],
            ['page' => 'home', 'section_name' => 'socials', 'key' => 'social_telegram', 'value' => ['ar' => 'https://t.me/moazalian', 'en' => 'https://t.me/moazalian'], 'type' => 'url'],
            ['page' => 'home', 'section_name' => 'socials', 'key' => 'social_twitter', 'value' => ['ar' => 'https://twitter.com/moazalian', 'en' => 'https://twitter.com/moazalian'], 'type' => 'url'],

            // Why Moaz Section
            ['page' => 'home', 'section_name' => 'why_moaz', 'key' => 'why_moaz_eyebrow', 'value' => ['ar' => 'لماذا معاذ عليان؟', 'en' => 'Why Moaz Alian?'], 'type' => 'text'],
            ['page' => 'home', 'section_name' => 'why_moaz', 'key' => 'why_moaz_title', 'value' => ['ar' => 'يستدل من مصادرهم — لا من كتبنا', 'en' => 'Uses Their Sources'], 'type' => 'text'],
            ['page' => 'home', 'section_name' => 'why_moaz', 'key' => 'why_moaz_subtitle', 'value' => ['ar' => 'منهجية علمية فريدة تعتمد على الكتاب المقدس والمراجع الأكاديمية الغربية والمخطوطات الأصلية', 'en' => 'A unique scientific methodology based on original sources.'], 'type' => 'textarea'],
            ['page' => 'home', 'section_name' => 'why_moaz', 'key' => 'why_moaz_quote', 'value' => ['ar' => 'أنا لا أقول لك: «صدّق لأني مسلم» — بل أقول: «هذا ما قاله علماؤكم وكُتبكم ومجامعكم. اذهب وتحقق بنفسك.» كل دليل في يدي هو من مصادرهم الأصلية.', 'en' => 'I don\'t tell you to believe because I\'m a Muslim, but because of what your own scholars say.'], 'type' => 'textarea'],

            // Features (under Why Moaz)
            ['page' => 'home', 'section_name' => 'why_moaz', 'key' => 'feature1_title', 'value' => ['ar' => 'مصادر أصلية', 'en' => 'Original Sources'], 'type' => 'text'],
            ['page' => 'home', 'section_name' => 'why_moaz', 'key' => 'feature1_desc', 'value' => ['ar' => 'يستشهد بـ Bart Ehrman وBaker Encyclopedia وCodex Sinaiticus — من مصادرهم أنفسهم', 'en' => 'Cites original academic western sources.'], 'type' => 'textarea'],
            ['page' => 'home', 'section_name' => 'why_moaz', 'key' => 'feature2_title', 'value' => ['ar' => 'لغات متعددة', 'en' => 'Multiple Languages'], 'type' => 'text'],
            ['page' => 'home', 'section_name' => 'why_moaz', 'key' => 'feature2_desc', 'value' => ['ar' => 'يقرأ اليونانية والعبرية واللاتينية للوصول للنصوص الأصلية دون وسيط', 'en' => 'Reads Greek, Hebrew, and Latin.'], 'type' => 'textarea'],
            ['page' => 'home', 'section_name' => 'why_moaz', 'key' => 'feature3_title', 'value' => ['ar' => 'ردود موثقة', 'en' => 'Documented Responses'], 'type' => 'text'],
            ['page' => 'home', 'section_name' => 'why_moaz', 'key' => 'feature3_desc', 'value' => ['ar' => 'كل رد مبني على دليل أكاديمي معتمد — لا رأي شخصي ولا عاطفة دينية', 'en' => 'Every response is based on accredited academic evidence.'], 'type' => 'textarea'],
            ['page' => 'home', 'section_name' => 'why_moaz', 'key' => 'feature4_title', 'value' => ['ar' => 'حوار مباشر', 'en' => 'Live Dialogue'], 'type' => 'text'],
            ['page' => 'home', 'section_name' => 'why_moaz', 'key' => 'feature4_desc', 'value' => ['ar' => 'يناظر قسيسين ومطارنة وأساتذة لاهوت في بث مباشر — شفافية تامة', 'en' => 'Debates priests, bishops, and theology professors live.'], 'type' => 'textarea'],

            // YT CTA Section
            ['page' => 'home', 'section_name' => 'yt_cta', 'key' => 'yt_cta_title', 'value' => ['ar' => 'تابع القناة على يوتيوب', 'en' => 'Follow the YouTube Channel'], 'type' => 'text'],
            ['page' => 'home', 'section_name' => 'yt_cta', 'key' => 'yt_cta_desc', 'value' => ['ar' => 'فيديوهات جديدة كل أسبوع — مناظرات ونقد كتابي وردود سريعة وبث مباشر. اشترك الآن لتصلك الإشعارات فور النشر.', 'en' => 'New videos every week. Subscribe now!'], 'type' => 'textarea'],
            
            // Donations Section
            ['page' => 'home', 'section_name' => 'donations', 'key' => 'donations_title', 'value' => ['ar' => 'تبرع لدعم هذه الرسالة', 'en' => 'Donate to Support This Message'], 'type' => 'text'],
            ['page' => 'home', 'section_name' => 'donations', 'key' => 'donations_subtitle', 'value' => ['ar' => 'مساهمتك تُعين على نشر الحق وتُمكّن من إنتاج محتوى أكاديمي متخصص في خدمة الإسلام', 'en' => 'Your contribution helps spread the truth.'], 'type' => 'textarea'],
        ];

        foreach ($sections as $section) {
            \App\Models\Section::updateOrCreate(
                ['key' => $section['key']],
                $section
            );
        }
    }
}
