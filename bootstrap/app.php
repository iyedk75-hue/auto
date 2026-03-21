<?php

use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);

        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'super-admin' => \App\Http\Middleware\SuperAdminMiddleware::class,
            'school-admin' => \App\Http\Middleware\SchoolAdminMiddleware::class,
            'candidate-access' => \App\Http\Middleware\CandidateAccessMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (PostTooLargeException $exception, Request $request) {
            if ($request->expectsJson()) {
                return null;
            }

            if (! $request->is('admin/courses') && ! preg_match('#^admin/courses/[^/]+$#', $request->path())) {
                return null;
            }

            return redirect()
                ->back()
                ->withErrors([
                    'audio' => __('ui.admin_courses.audio_upload_too_large', [
                        'size' => ini_get('upload_max_filesize') ?: '2M',
                    ]),
                ]);
        });
    })->create();
