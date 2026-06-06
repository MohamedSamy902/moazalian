<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\CourseController;
use App\Http\Controllers\Frontend\ArticleController;

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ 
            \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class, 
            \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class, 
            \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationViewPath::class 
        ]
    ],
    function () {
        Route::get('/', [HomeController::class, 'index'])->name('home');
        
        // Courses
        Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/{slug}', [CourseController::class, 'show'])->name('courses.show');

        // Videos
        Route::get('/videos', [\App\Http\Controllers\Frontend\VideoController::class, 'index'])->name('videos.index');
        Route::get('/videos/{slug}', [\App\Http\Controllers\Frontend\VideoController::class, 'show'])->name('videos.show');

        // Articles
        Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
        Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('articles.show');
    }
);
