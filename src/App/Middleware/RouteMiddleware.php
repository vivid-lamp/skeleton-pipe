<?php

declare(strict_types=1);

namespace VividLamp\PipeSkeleton\App\Middleware;

use FastRoute\Dispatcher;
use VividLamp\PipeSkeleton\Bootstrap\App;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

/**
 * 路由解决
 * @author zhanglihui
 */
class RouteMiddleware implements MiddlewareInterface
{
    /** @var Dispatcher  */
    protected $dispatcher;

    /** @var App  */
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;

        $dispatcher = simpleDispatcher(function (RouteCollector $collector) {
            $this->route($collector);
        });

        $this->dispatcher = $dispatcher;
    }

    /**
     * 路由注册
     * @param RouteCollector $collector
     */
    protected function route(RouteCollector $collector)
    {
        $callback = require $this->app->getBasePath() . 'Config/route.php';
        $callback($collector);
    }


    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $uri = $request->getUri()->getPath();
        $method = $request->getMethod();


        $info = $this->dispatcher->dispatch($method, $uri);
        $routeStatus = $info[0];
        switch ($routeStatus) {
            case Dispatcher::NOT_FOUND:
            case Dispatcher::METHOD_NOT_ALLOWED:
                $request = $request->withAttribute('routeResult', $routeStatus);
                return $handler->handle($request);
        }

        $routeHandler = $info[1];
        $routeParam = $info[2] ?? [];
        
        foreach ($routeParam as $key => $val) {
            $request = $request->withAttribute($key, $val);
        }

        if (is_array($routeHandler)) {
            $middleware = $routeHandler;
            $routeHandler = array_pop($middleware);
        }

        /* 将控制器方法的执行放在中间件队列头部 */
        $this->app->pipe(function (ServerRequestInterface $request) use ($routeHandler) {
            return $this->app->getContainer()->call($routeHandler, [ServerRequestInterface::class => $request]);
        }, true);

        /* 将中间件反转过来添加到队列头部 */
        if (isset($middleware) && count($middleware) > 0) {
            $this->app->pipe(end($middleware), true);
            while ($foo = prev($middleware)) {
                $this->app->pipe($foo, true);
            }
        }

        return $handler->handle($request);
    }
}
