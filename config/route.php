<?php

declare(strict_types=1);

namespace VividLamp\PipeSkeleton\Config;

use FastRoute\RouteCollector;
use VividLamp\PipeSkeleton\App\Controller\Home;
use VividLamp\PipeSkeleton\App\Middleware\RouteMiddleware;

/**
 * 辅助返回形如 [middleware1, middleware1, ..., handler] 的数据结构
 * @author zhanglihui
 */
$aggregation = new class {
    protected $middleware;
    public function middleware(...$middleware)
    {
        $this->middleware = $middleware;
        return $this;
    }
    /** @return string[]|string */
    public function handler(string $handler)
    {
        return $this->middleware ? array_merge($this->middleware, [$handler]) : $handler;
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

    $collector->get('/', $aggregation->handler(Home::class . '@index'));
};
