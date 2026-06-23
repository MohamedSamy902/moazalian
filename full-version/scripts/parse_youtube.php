<?php
/**
 * parse_youtube.php
 * يقرأ ملف yt_videos.txt ويولّد Seeder كامل للـ Laravel
 * تشغيل: php scripts/parse_youtube.php
 */

$inputFile = '/tmp/yt_videos.txt';
$lines     = file($inputFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$videos    = [];

foreach ($lines as $line) {
    $parts = explode('|||', $line);
    if (count($parts) < 2) continue;

    $youtubeId = trim($parts[0]);
    $title     = trim($parts[1]);
    $dateRaw   = trim($parts[2] ?? 'NA');
    $duration  = trim($parts[3] ?? '0');

    // تحويل التاريخ من YYYYMMDD إلى Y-m-d
    $publishedAt = null;
    if ($dateRaw !== 'NA' && strlen($dateRaw) === 8) {
        $publishedAt = substr($dateRaw, 0, 4) . '-' . substr($dateRaw, 4, 2) . '-' . substr($dateRaw, 6, 2);
    }

    // تحويل الـ duration من ثواني إلى mm:ss
    $durationStr = null;
    $durSec = (int) floatval($duration);
    if ($durSec > 0) {
        $hours   = intdiv($durSec, 3600);
        $mins    = intdiv($durSec % 3600, 60);
        $secs    = $durSec % 60;
        $durationStr = $hours > 0
            ? sprintf('%d:%02d:%02d', $hours, $mins, $secs)
            : sprintf('%d:%02d', $mins, $secs);
    }

    // توليد slug من الـ ID
    $slug = 'yt-' . strtolower($youtubeId);

    // تحديد اللغة بشكل تقريبي
    $isArabic = preg_match('/[\x{0600}-\x{06FF}]/u', $title);
    $lang = $isArabic ? 'ar' : 'en';

    $videoUrl = "https://www.youtube.com/watch?v={$youtubeId}";
    $thumbnail = "https://i.ytimg.com/vi/{$youtubeId}/hqdefault.jpg";

    $videos[] = [
        'youtube_id'   => $youtubeId,
        'title_ar'     => $isArabic ? $title : null,
        'title_en'     => $isArabic ? null : $title,
        'slug'         => $slug,
        'video_url'    => $videoUrl,
        'thumbnail'    => $thumbnail,
        'duration'     => $durationStr,
        'published_at' => $publishedAt,
        'video_type'   => 'external',
        'lang'         => $lang,
    ];
}

echo "✅ Found: " . count($videos) . " videos\n";

// توليد ملف الـ Seeder
$seederPath = __DIR__ . '/../database/seeders/YouTubeVideosSeeder.php';

$rows = '';
foreach ($videos as $v) {
    $titleAr = addslashes($v['title_ar'] ?? '');
    $titleEn = addslashes($v['title_en'] ?? ($v['title_ar'] ?? ''));
    $slug    = addslashes($v['slug']);
    $url     = addslashes($v['video_url']);
    $thumb   = addslashes($v['thumbnail']);
    $dur     = $v['duration'] ? "'" . addslashes($v['duration']) . "'" : 'null';
    $date    = $v['published_at'] ? "'" . $v['published_at'] . "'" : 'null';
    $ytId    = addslashes($v['youtube_id']);

    $rows .= <<<PHP
            [
                'title'        => json_encode(['ar' => '{$titleAr}', 'en' => '{$titleEn}'], JSON_UNESCAPED_UNICODE),
                'slug'         => '{$slug}',
                'video_url'    => '{$url}',
                'thumbnail'    => '{$thumb}',
                'duration'     => {$dur},
                'published_at' => {$date},
                'video_type'   => 'external',
                'views'        => 0,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],

PHP;
}

$count = count($videos);

$seederContent = <<<SEEDER
<?php

namespace Database\\Seeders;

use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;

/**
 * YouTubeVideosSeeder
 * Auto-generated from @mo3az3lian YouTube channel
 * Total: {$count} videos
 */
class YouTubeVideosSeeder extends Seeder
{
    public function run(): void
    {
        // تجنب التكرار — بالنسبة للـ slug فقط
        \$existingSlugs = DB::table('videos')->pluck('slug')->toArray();

        \$videos = [
{$rows}
        ];

        \$toInsert = array_filter(\$videos, fn(\$v) => !in_array(\$v['slug'], \$existingSlugs));

        if (empty(\$toInsert)) {
            \$this->command->info('✅ All videos already exist — nothing to insert.');
            return;
        }

        foreach (array_chunk(\$toInsert, 100) as \$chunk) {
            DB::table('videos')->insert(\$chunk);
        }

        \$this->command->info('✅ Inserted: ' . count(\$toInsert) . ' videos from YouTube channel');
    }
}
SEEDER;

file_put_contents($seederPath, $seederContent);
echo "✅ Seeder written to: {$seederPath}\n";
echo "🚀 Run: php artisan db:seed --class=YouTubeVideosSeeder\n";
