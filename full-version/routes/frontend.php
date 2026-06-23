<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\CourseController;
use App\Http\Controllers\Frontend\ArticleController;
use App\Http\Controllers\Frontend\VideoController;
use App\Http\Controllers\Frontend\BookController;
// use App\Http\Controllers\Frontend\DebateController; // [DISABLED] مناظرات — الفيديوهات تغطي هذا القسم
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\NewsletterController;
use App\Http\Controllers\Frontend\StaticPageController;

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [
            \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class,
            \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
            \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationViewPath::class,
        ]
    ],
    function () {

        // ─── Home ──────────────────────────────────────────────────────────────
        Route::get('/', [HomeController::class, 'index'])->name('home');

        // ─── Videos ────────────────────────────────────────────────────────────
        Route::get('/videos', [VideoController::class, 'index'])->name('videos.index');
        Route::get('/videos/{slug}', [VideoController::class, 'show'])->name('videos.show');

        // ─── Articles ──────────────────────────────────────────────────────────
        Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
        Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('articles.show');

        // ─── Courses ───────────────────────────────────────────────────────────
        Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/{slug}', [CourseController::class, 'show'])->name('courses.show');

        // ─── Books ─────────────────────────────────────────────────────────────
        Route::get('/books', [BookController::class, 'index'])->name('books.index');

        // ─── Debates [DISABLED] ─────────────────────────────────────────────────
        // المناظرات متوفرة ضمن الفيديوهات — تم إيقاف الصفحة المستقلة مؤقتاً
        // Route::get('/debates', [DebateController::class, 'index'])->name('debates.index');
        // Route::get('/debates/{debate}', [DebateController::class, 'show'])->name('debates.show');

        // ─── Static Pages ──────────────────────────────────────────────────────
        Route::get('/about', [StaticPageController::class, 'about'])->name('about');
        Route::get('/dawah', [StaticPageController::class, 'dawah'])->name('dawah');
        Route::get('/references', [StaticPageController::class, 'references'])->name('references');
        Route::get('/live', [StaticPageController::class, 'live'])->name('live');

        // ─── Contact ───────────────────────────────────────────────────────────
        Route::get('/contact', [ContactController::class, 'index'])->name('contact');
        Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

        // ─── Newsletter (AJAX) ─────────────────────────────────────────────────
        Route::post('/newsletter', [NewsletterController::class, 'store'])->name('newsletter.store');
    }
);
