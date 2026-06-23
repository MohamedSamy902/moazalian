<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Book;
use App\Models\Debate;
use App\Models\QuickResponse;
use App\Models\Section;
use App\Models\Video;
use App\Observers\HomeDataObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        Model::preventLazyLoading(!app()->isProduction());

        // Auto-invalidate homepage cache when any content model changes
        Video::observe(HomeDataObserver::class);
        Article::observe(HomeDataObserver::class);
        Debate::observe(HomeDataObserver::class);
        Book::observe(HomeDataObserver::class);
        QuickResponse::observe(HomeDataObserver::class);

        // Share social links with ALL frontend layout views (footer, header)
        // Cached independently so the footer never shows hardcoded fallbacks
        View::composer('frontend.layouts.*', function ($view) {
            if (!$view->offsetExists('sections')) {
                $socialSections = Cache::remember('frontend_social_sections', now()->addHour(), function () {
                    return Section::whereIn('key', [
                        'social_youtube',
                        'social_facebook',
                        'social_telegram',
                        'social_twitter',
                    ])->get()->keyBy('key');
                });
                $view->with('sections', $socialSections);
            }
        });

        Vite::useStyleTagAttributes(function (?string $src, string $url, ?array $chunk, ?array $manifest) {
            if ($src !== null) {
                return [
                    'class' => preg_match("/(resources\/assets\/vendor\/scss\/(rtl\/)?core)-?.*/i", $src) ? 'template-customizer-core-css' : (preg_match("/(resources\/assets\/vendor\/scss\/(rtl\/)?theme)-?.*/i", $src) ? 'template-customizer-theme-css' : '')
                ];
            }
            return [];
        });
    }
}

