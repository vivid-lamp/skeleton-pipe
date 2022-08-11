<?php

declare(strict_types=1);

namespace VividLamp\PipeSkeleton\Config;

use FastRoute\RouteCollector;
use VividLamp\PipeSkeleton\App\Controller\Home;
use VividLamp\PipeSkeleton\App\Middleware\RouteMiddleware;

/**
 * @author zhanglihui
 */
$aggregation = new class {
    protected $middleware;
    public function middleware(...$middleware)
    {
        $this->middleware = $middleware;
        return $this;
    }
    /**
     * @param string $handler
     * @param string|null $name 路由名称，如 home.index
     * @return array
     */
    public function handler(string $handler, ?string $name = null): array
    {
        return ['handler' => $handler, 'middleware' => $this->middleware, 'name' => $name];
    }
};

/**
 * 路由注册
 * @param RouteCollector $collector
 * @see RouteMiddleware
 * @author zhanglihui
 */
return function (RouteCollector $collector) use ($aggregation) {
    
        // $aggregation->middleware(AuthMiddleware::class);

    $collector->get('/', $aggregation->handler(Home::class . '@index', 'home.index'));
};
