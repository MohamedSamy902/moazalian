<?php

namespace App\Console\Commands\YouTube;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncYouTubeViewsCommand extends Command
{
    protected $signature = 'youtube:sync-views
                            {--batch=50 : Number of videos to process per yt-dlp call}
                            {--limit=0  : Max videos to update (0 = all)}
                            {--force    : Update even recently synced videos}';

    protected $description = 'Fetch YouTube view counts for all imported videos and update the database';

    public function handle(): int
    {
        $this->info('🎬 YouTube Views Sync — starting...');

        // Check yt-dlp availability
        $ytdlp = trim(shell_exec('which yt-dlp 2>/dev/null') ?? '');
        if (empty($ytdlp)) {
            $this->error('❌ yt-dlp not found. Install with: pip3 install yt-dlp --break-system-packages');
            return self::FAILURE;
        }

        // Get all YouTube videos (slug starts with yt-)
        $query = DB::table('videos')
            ->whereNull('deleted_at')
            ->where('slug', 'like', 'yt-%')
            ->whereNotNull('video_url')
            ->select('id', 'slug', 'video_url', 'views');

        if (!$this->option('force')) {
            // Skip videos updated in last 23 hours (avoid redundant updates in same day)
            $query->where(function ($q) {
                $q->whereNull('updated_at')
                  ->orWhere('updated_at', '<', now()->subHours(23));
            });
        }

        $limit = (int) $this->option('limit');
        if ($limit > 0) {
            $query->limit($limit);
        }

        $videos = $query->get();
        $total  = $videos->count();

        if ($total === 0) {
            $this->info('✅ All videos are up-to-date. Use --force to re-sync all.');
            return self::SUCCESS;
        }

        $this->info("📊 Processing {$total} videos...");
        $batchSize = (int) $this->option('batch');
        $updated   = 0;
        $errors    = 0;
        $bar       = $this->output->createProgressBar($total);
        $bar->start();

        // Process in batches — extract YouTube IDs from video_url
        $batches = $videos->chunk($batchSize);

        foreach ($batches as $batch) {
            // Build URL list
            $urlMap = []; // ytId => dbId
            foreach ($batch as $video) {
                $ytId = $this->extractYouTubeId($video->video_url);
                if ($ytId) {
                    $urlMap[$ytId] = $video->id;
                }
            }

            if (empty($urlMap)) {
                $bar->advance($batch->count());
                continue;
            }

            // Build yt-dlp URL list (space-separated YouTube URLs)
            $urls = implode(' ', array_map(
                fn($id) => "https://www.youtube.com/watch?v={$id}",
                array_keys($urlMap)
            ));

            // Fetch view counts — no download, just metadata
            $cmd    = "yt-dlp --no-warnings --skip-download --print '%(id)s|||%(view_count)s' {$urls} 2>/dev/null";
            $output = shell_exec($cmd);

            if (empty($output)) {
                $errors += $batch->count();
                $bar->advance($batch->count());
                continue;
            }

            // Parse output and update DB
            $lines = array_filter(explode("\n", trim($output)));
            foreach ($lines as $line) {
                $parts   = explode('|||', $line);
                if (count($parts) < 2) continue;

                $ytId      = trim($parts[0]);
                $viewCount = trim($parts[1]);

                if (!isset($urlMap[$ytId]) || !is_numeric($viewCount)) continue;

                $dbId = $urlMap[$ytId];
                DB::table('videos')->where('id', $dbId)->update([
                    'views'      => (int) $viewCount,
                    'updated_at' => now(),
                ]);
                $updated++;
            }

            $bar->advance($batch->count());

            // Small delay between batches to avoid rate limiting
            if ($batch->count() >= $batchSize) {
                sleep(2);
            }
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("✅ Sync complete: {$updated} updated, {$errors} errors out of {$total} total.");

        Log::info("YouTube views sync: {$updated}/{$total} updated", [
            'errors' => $errors,
            'forced' => $this->option('force'),
        ]);

        return self::SUCCESS;
    }

    private function extractYouTubeId(string $url): ?string
    {
        // Handle: https://www.youtube.com/watch?v=XXXXX
        if (str_contains($url, 'youtube.com/watch?v=')) {
            parse_str(parse_url($url, PHP_URL_QUERY), $vars);
            return $vars['v'] ?? null;
        }
        // Handle: https://youtu.be/XXXXX
        if (str_contains($url, 'youtu.be/')) {
            $path = parse_url($url, PHP_URL_PATH);
            return ltrim($path, '/');
        }
        return null;
    }
}
