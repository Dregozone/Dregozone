<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\TrackUserActivity;
use App\Http\Middleware\VerifyStatsToken;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Livewire\Features\SupportFileUploads\MissingFileUploadsTraitException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'stats.token' => VerifyStatsToken::class,
        ]);
        $middleware->appendToGroup('web', TrackUserActivity::class);

        // Proxies come from config/trustedproxy.php (Cloudflare). Only the client IP and scheme are
        // taken from them: Cloudflare passes visitor-supplied X-Forwarded-Host/Port straight through.
        $middleware->trustProxies(headers: Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_PROTO);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Bots probe public Livewire forms with forged upload calls. Livewire already rejects them,
        // so answer with a 400 instead of reporting an unhandled 500.
        $exceptions->dontReport(MissingFileUploadsTraitException::class);

        $exceptions->render(function (MissingFileUploadsTraitException $exception) {
            abort(400);
        });
    })->create();
