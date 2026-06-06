<?php

use App\Http\Controllers\Dashboard\AdminAuthController;
use App\Http\Controllers\Dashboard\CourseController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AdminAuthController::class, 'login']);
Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

Route::middleware([
    'auth:admin',
    \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class,
    \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
    \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationViewPath::class,
])->prefix(LaravelLocalization::setLocale() . '/admin')->group(function () {
    Route::get('/', function () {
        return view('dashboard.home');
    })->name('home');

    // Content Management
    Route::resource('courses', CourseController::class);
    Route::resource('videos', \App\Http\Controllers\Dashboard\VideoController::class);
    Route::resource('quick-responses', \App\Http\Controllers\Dashboard\QuickResponseController::class);

    // Roles & Permissions
    Route::post('roles/{role}/assign-user', [\App\Http\Controllers\Dashboard\RoleController::class, 'assignUser'])->name('roles.assignUser');
    Route::delete('roles/{role}/remove-user/{admin}', [\App\Http\Controllers\Dashboard\RoleController::class, 'removeUser'])->name('roles.removeUser');
    Route::resource('roles', \App\Http\Controllers\Dashboard\RoleController::class);

    // Admins Management
    Route::resource('admins', \App\Http\Controllers\Dashboard\AdminController::class);

    // Users Management
    Route::resource('users', \App\Http\Controllers\Dashboard\UserController::class);

    // Sections Management
    Route::get('sections', [\App\Http\Controllers\Dashboard\SectionController::class, 'index'])->name('sections.index');
    Route::get('sections/{section_name}/edit', [\App\Http\Controllers\Dashboard\SectionController::class, 'edit'])->name('sections.edit');
    Route::put('sections/{section_name}', [\App\Http\Controllers\Dashboard\SectionController::class, 'update'])->name('sections.update');
  }
);
