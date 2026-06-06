<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware(['web'])
                ->name('admin.')
                ->group(base_path('routes/admin.php'));

            Route::middleware(['web'])
                ->group(base_path('routes/frontend.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role'                  => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission'            => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission'    => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'localize'              => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes::class,
            'localizationRedirect'  => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
            'localeSessionRedirect' => \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class,
            'localeCookieRedirect'  => \Mcamara\LaravelLocalization\Middleware\LocaleCookieRedirect::class,
            'localeViewPath'        => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationViewPath::class,
        ]);

        $middleware->redirectGuestsTo(fn (\Illuminate\Http\Request $request) =>
            $request->is('admin*') || $request->is('*/admin*') ? route('admin.login') : (Route::has('login') ? route('login') : route('admin.login'))
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // ─── 404: Model Not Found ───────────────────────────────────────────
        $exceptions->render(function (\Illuminate\Database\Eloquent\ModelNotFoundException $e, \Illuminate\Http\Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'العنصر المطلوب غير موجود.'], 404);
            }
            return response()->view('errors.404', [], 404);
        });

        // ─── 403: Unauthorized / Permission Denied ──────────────────────────
        $exceptions->render(function (\Illuminate\Auth\Access\AuthorizationException $e, \Illuminate\Http\Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'غير مصرح لك بتنفيذ هذه العملية.'], 403);
            }
            return response()->view('errors.403', ['message' => $e->getMessage()], 403);
        });

        // ─── 422: Validation Errors (for AJAX requests) ─────────────────────
        $exceptions->render(function (\Illuminate\Validation\ValidationException $e, \Illuminate\Http\Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'البيانات المدخلة غير صحيحة.',
                    'errors'  => $e->errors(),
                ], 422);
            }
        });

        // ─── 500: Generic Exceptions ─────────────────────────────────────────
        $exceptions->render(function (\Throwable $e, \Illuminate\Http\Request $request) {
            // Log the real error with full context
            \Illuminate\Support\Facades\Log::error('Unhandled Exception', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'url'     => $request->fullUrl(),
                'method'  => $request->method(),
                'user_id' => auth('admin')->id() ?? auth()->id(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => app()->isProduction()
                        ? 'حدث خطأ في الخادم. يرجى المحاولة مرة أخرى لاحقاً.'
                        : $e->getMessage(),
                ], 500);
            }
        });
    })->create();

