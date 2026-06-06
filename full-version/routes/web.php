<?php

use App\Http\Controllers\Dashboard\HomeController;
use Illuminate\Support\Facades\Route;

// Redirect root to frontend home
Route::get('/', function () {
    return redirect()->route('home');
});

// Dashboard home (protected by auth:admin middleware in admin.php)
Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard.home');
