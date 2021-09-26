<?php

declare(strict_types=1);

use VividLamp\PipeSkeleton\App\Middleware\ExceptionHandlerMiddleware;
use VividLamp\PipeSkeleton\App\Middleware\InitializeMiddleware;
use VividLamp\PipeSkeleton\App\Middleware\RouteMiddleware;
use VividLamp\PipeSkeleton\App\Middleware\RouteMissedMiddleware;
use VividLamp\PipeSkeleton\Bootstrap\App;


// Delegate static file requests back to the PHP built-in webserver
if (PHP_SAPI === 'cli-server' && $_SERVER['SCRIPT_FILENAME'] !== __FILE__) {
    return false;
}

/**
 * Self-called anonymous function that creates its own scope and keeps the global namespace clean.
 */
(function () {
    require_once __DIR__ . '/../vendor/autoload.php';

    $app = new App(__DIR__ . '/../');

    $app->pipe(ExceptionHandlerMiddleware::class);

    $app->pipe(InitializeMiddleware::class);

    $app->pipe(RouteMiddleware::class);

    $app->pipe(RouteMissedMiddleware::class);

    $app->run();
})();
