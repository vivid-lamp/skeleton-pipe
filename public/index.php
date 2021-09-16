<?php
declare(strict_types=1);

namespace Framework;

use Framework\App\Middleware\ExceptionHandlerMiddleware;
use Framework\App\Middleware\InitializeMiddleware;
use Framework\App\Middleware\RouteMiddleware;
use Framework\App\Middleware\RouteMissedMiddleware;
use Framework\Bootstrap\App;

$app = new App(__DIR__ . '/');


error_reporting(E_ALL);

$app->pipe(ExceptionHandlerMiddleware::class);

$app->pipe(InitializeMiddleware::class);

$app->pipe(RouteMiddleware::class);

$app->pipe(RouteMissedMiddleware::class);

$app->run();
