<?php

namespace App\Providers;

use App\Repositories\Implementations\ArticleRepository;
use App\Repositories\Implementations\BookRepository;
use App\Repositories\Implementations\CourseRepository;
use App\Repositories\Implementations\DebateRepository;
use App\Repositories\Implementations\QuickReplyRepository;
use App\Repositories\Implementations\QuickResponseRepository;
use App\Repositories\Implementations\VideoRepository;
use App\Repositories\Interfaces\ArticleRepositoryInterface;
use App\Repositories\Interfaces\BookRepositoryInterface;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\DebateRepositoryInterface;
use App\Repositories\Interfaces\QuickReplyRepositoryInterface;
use App\Repositories\Interfaces\QuickResponseRepositoryInterface;
use App\Repositories\Interfaces\VideoRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * All Interface → Implementation bindings.
     * To register a new repository, add one entry here — no other file changes needed.
     */
    private array $repositories = [
        CourseRepositoryInterface::class        => CourseRepository::class,
        ArticleRepositoryInterface::class       => ArticleRepository::class,
        VideoRepositoryInterface::class         => VideoRepository::class,
        BookRepositoryInterface::class          => BookRepository::class,
        DebateRepositoryInterface::class        => DebateRepository::class,
        QuickReplyRepositoryInterface::class    => QuickReplyRepository::class,
        QuickResponseRepositoryInterface::class => QuickResponseRepository::class,
    ];

    public function register(): void
    {
        foreach ($this->repositories as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
    }

    public function boot(): void {}
}
