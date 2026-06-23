<?php

namespace App\Services\Frontend;

use App\Models\Book;
use App\Models\Debate;
use App\Models\QuickResponse;
use App\Models\Section;
use App\Models\Video;
use App\Repositories\Interfaces\ArticleRepositoryInterface;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\VideoRepositoryInterface;
use App\Support\CacheKeys;
use Illuminate\Support\Facades\Cache;

class HomeService
{
    public function __construct(
        private readonly CourseRepositoryInterface $courseRepo,
        private readonly ArticleRepositoryInterface $articleRepo,
        private readonly VideoRepositoryInterface $videoRepo,
    ) {}

    public function getHomeData(): array
    {
        // return Cache::remember(CacheKeys::HOME_DATA, now()->addHour(), function () {
            return [
                // Already connected
                'latestCourses'        => $this->courseRepo->getPublishedPaginated(3),
                'latestVideos'         => $this->videoRepo->getPublishedPaginated(3),

                // Fixed — was hardcoded in view
                'latestArticles'       => $this->articleRepo->paginate(3),
                // 'latestDebates' => Debate::latest()->take(3)->get(), // [DISABLED] المناظرات ضمن الفيديوهات
                'latestBooks'          => Book::ordered()->take(3)->get(),
                'featuredBooks'        => Book::featured()->ordered()->take(5)->get(),  // homepage books slider (max 5)
                'latestQuickResponses' => QuickResponse::where('is_published', true)
                                            ->latest()
                                            ->take(3)
                                            ->get(),

                // CMS sections (home page)
                'sections'             => Section::where('page', 'home')
                                            ->get()
                                            ->keyBy('key'),
            ];
        // });
    }
}
