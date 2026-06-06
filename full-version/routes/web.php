<?php
use App\Http\Controllers\Dashboard\HomeController;
use App\Http\Controllers\language\LanguageController;
use Illuminate\Support\Facades\Route;

// Main Page Route
Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard.home');

// Fix-It Dashboard Routes
// locale
Route::get('/lang/{locale}', [LanguageController::class, 'swap']);

// ── DEV ONLY: auto-login & logout ────────────────────────────────────────────
// Remove these routes before deploying to production!
if (app()->isLocal()) {
    Route::get('/dev-login/{email?}', function (string $email = 'admin@fixit.com') {
        $user = \App\Models\User::where('email', $email)->firstOrFail();
        auth()->login($user);
        return redirect('/dashboard/centers');
    })->name('dev.login');

    Route::get('/logout', function () {
        auth()->logout();
        return redirect('/dev-login');
    })->name('logout');

    Route::post('/logout', function () {
        auth()->logout();
        return redirect('/dev-login');
    });
}
