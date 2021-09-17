<?php

declare(strict_types=1);

// namespace VividLamp\PipeSkeleton;

use VividLamp\PipeSkeleton\App\Middleware\ExceptionHandlerMiddleware;
use VividLamp\PipeSkeleton\App\Middleware\InitializeMiddleware;
use VividLamp\PipeSkeleton\App\Middleware\RouteMiddleware;
use VividLamp\PipeSkeleton\App\Middleware\RouteMissedMiddleware;
use VividLamp\PipeSkeleton\Bootstrap\App;


error_reporting(E_ALL);

// 避免污染全局变量
(function () {

    require_once __DIR__ . '/../vendor/autoload.php';

    $app = new App(__DIR__ . '/../');

    $app->pipe(ExceptionHandlerMiddleware::class);

    $app->pipe(InitializeMiddleware::class);

    $app->pipe(RouteMiddleware::class);

    $app->pipe(RouteMissedMiddleware::class);

    $app->run();
})();
