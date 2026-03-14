<?php

use Filament\Facades\Filament;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectUsersTo(function (Request $request) {
            if (Filament::auth()->check()) {
                if (Filament::auth()->user()->hasRole('admin')) {
                    return '/admin'; // توجيه الأدمن للوحته
                }
                if (Filament::auth()->user()->hasRole('instructor')) {
                    return '/instructor'; // توجيه المدرب للوحته
                }
            }
            return null; // المسار الافتراضي للطلاب أو الزوار
        });
    
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
