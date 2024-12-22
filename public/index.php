<?php

declare(strict_types=1);

use Acme\Application;
use Psr\Container\ContainerInterface;

if (PHP_SAPI === 'cli-server' && $_SERVER['SCRIPT_FILENAME'] !== __FILE__) {
    return false;
}


chdir(dirname(__DIR__));

require_once 'vendor/autoload.php';

/** @var ContainerInterface $container */
$container = require 'config/container.php';

/** @var Application $app */
try {
    $app = $container->get(Application::class);
    $app->http()->serve();
} catch (\Psr\Container\NotFoundExceptionInterface|\Psr\Container\ContainerExceptionInterface $e) {

}


