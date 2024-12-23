<?php

declare(strict_types=1);

namespace VividLamp\PipeSkeleton\Config;

use League\Route\Router;
use League\Route\Strategy\StrategyInterface;
use Psr\Http\Server\RequestHandlerInterface;

return static function (StrategyInterface $routeStrategy): RequestHandlerInterface {
    $router = new Router();
    $router->setStrategy($routeStrategy);

    $router->get('/', [\VividLamp\PipeSkeleton\Operation\Controller\Index::class, 'index']);
    $router->get('/{id}', [\VividLamp\PipeSkeleton\Operation\Controller\Index::class, 'show']);

    return $router;
};
