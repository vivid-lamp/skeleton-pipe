<?php

declare(strict_types=1);

// Delegate static file requests back to the PHP built-in webserver
if (PHP_SAPI === 'cli-server' && $_SERVER['SCRIPT_FILENAME'] !== __FILE__) {
    return false;
}


chdir(dirname(__DIR__));

require_once 'vendor/autoload.php';

/** @var \Psr\Container\ContainerInterface $container */
$container = require 'config/container.php';

/** @var \Acme\Application $application */
$application = $container->get(\Acme\Application::class);

$application->pipe(\Acme\App\Middleware\ExceptionHandlerMiddleware::class);
$application->pipe(\Acme\App\Middleware\InitializeMiddleware::class);
$application->pipe(\Mezzio\Helper\BodyParams\BodyParamsMiddleware::class);
$application->pipe(\Acme\App\Middleware\RouteMiddleware::class);

$application->run();
