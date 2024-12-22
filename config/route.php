<?php

declare(strict_types=1);

namespace VividLamp\PipeSkeleton\Config;

use Acme\App\Controller\Index;
use Acme\App\Middleware\ExceptionHandlerMiddleware;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;

return static function (ContainerInterface $container): RequestHandlerInterface {

	$strategy = new ApplicationStrategy();
	$strategy->setContainer($container);

	$router = new Router();
	$router->setStrategy($strategy);

    $router->lazyMiddleware(ExceptionHandlerMiddleware::class);

	$router->get('/', Index::class);

	return $router;
};
