<?php
namespace App\Services\Frontend;

use App\Repositories\Interfaces\ArticleRepositoryInterface;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\VideoRepositoryInterface;
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
        return Cache::remember('home.data', now()->addHour(), function () {
            return [
                'latestCourses'  => $this->courseRepo->getPublishedPaginated(3),
                'latestArticles' => $this->articleRepo->paginate(3),
                'latestVideos'   => $this->videoRepo->getPublishedPaginated(3),
                'sections'       => \App\Models\Section::where('page', 'home')->get()->keyBy('key'),
            ];
        });
    }
}
