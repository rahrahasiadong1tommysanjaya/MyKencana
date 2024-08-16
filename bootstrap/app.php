<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Exceptions\UnauthorizedException;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\LocaleMiddleware;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\CheckDatabase;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    web: __DIR__ . '/../routes/web.php',
    commands: __DIR__ . '/../routes/console.php',
    health: '/up',
  )
  ->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
      CheckDatabase::class,
      RedirectIfAuthenticated::class,
      LocaleMiddleware::class,
      PreventBackHistory::class,
    ]);
    $middleware->alias([
      'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
      'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
      'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
    ]);
  })
  ->withExceptions(function (Exceptions $exceptions) {
    $exceptions->render(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, Request $request) {
      if ($request->expectsJson()) {
          return response()->json([
              'message' => 'Anda tidak memiliki izin pada fitur ini menggunakan kredensial yang Anda berikan
                saat login.',
          ], 403);
      }else{
        return response()->view("pages.pages-misc-not-authorized", [], 403);
      }
    });
  })->create();
