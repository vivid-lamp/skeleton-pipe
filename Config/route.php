<?php

namespace Framework\Config;

use FastRoute\RouteCollector;
use Framework\App\Admin\Controller\SeoSsi;
use Framework\App\Admin\Middleware\AuthMiddleware;
use Framework\App\Middleware\RouteMiddleware;

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
    
        $aggregation->middleware(AuthMiddleware::class);

        $collector->addRoute('GET', '/', $aggregation->handler(SeoSsi::class . '@list'));
       
};
