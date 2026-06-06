<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Implementations\CourseRepository;

use App\Repositories\Interfaces\ArticleRepositoryInterface;
use App\Repositories\Implementations\ArticleRepository;

use App\Repositories\Interfaces\VideoRepositoryInterface;
use App\Repositories\Implementations\VideoRepository;

use App\Repositories\Interfaces\BookRepositoryInterface;
use App\Repositories\Implementations\BookRepository;

use App\Repositories\Interfaces\DebateRepositoryInterface;
use App\Repositories\Implementations\DebateRepository;

use App\Repositories\Interfaces\QuickReplyRepositoryInterface;
use App\Repositories\Implementations\QuickReplyRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
        $this->app->bind(ArticleRepositoryInterface::class, ArticleRepository::class);
        $this->app->bind(VideoRepositoryInterface::class, VideoRepository::class);
        $this->app->bind(BookRepositoryInterface::class, BookRepository::class);
        $this->app->bind(DebateRepositoryInterface::class, DebateRepository::class);
        $this->app->bind(QuickReplyRepositoryInterface::class, QuickReplyRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\QuickResponseRepositoryInterface::class, \App\Repositories\Implementations\QuickResponseRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
