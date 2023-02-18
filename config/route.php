<?php

declare(strict_types=1);

namespace VividLamp\PipeSkeleton\Config;

use Acme\App\Controller\Index;
use League\Route\Router;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;

return static function (ContainerInterface $container): RequestHandlerInterface {

	$strategy = new \League\Route\Strategy\ApplicationStrategy();
	$strategy->setContainer($container);

	$router = new Router();
	$router->setStrategy($strategy);

	$router->get('/', Index::class);


	return $router;
};
