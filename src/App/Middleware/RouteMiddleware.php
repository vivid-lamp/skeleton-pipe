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
        $callback = require $this->app->getBasePath() . 'config/route.php';
        $callback($collector);
    }


    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $routeResult = $this->dispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());

        if ($routeResult[0] !== Dispatcher::FOUND) {
            $request = $request->withAttribute('routeResult', $routeResult[0]);
            return $handler->handle($request);
        }

        /* 路由参数 */
        if (isset($routeResult[2])) {
            foreach ($routeResult[2] as $key => $val) {
                $request = $request->withAttribute($key, $val);
            }
        }

        /* 获取路由配置里手动传入的参数 */
        $middleware = $routeResult[1]['middleware'];
        $routeHandler = $routeResult[1]['handler'];
        $request = $request->withAttribute('routeName', $routeResult[1]['name']);

        /* 将控制器方法的执行放在中间件队列头部 */
        $this->app->pipe(function (ServerRequestInterface $request) use ($routeHandler) {
            return $this->app->getContainer()->call($routeHandler, [ServerRequestInterface::class => $request]);
        }, true);

        /* 将路由中间件添加到队列头部 */
        if (isset($middleware) && count($middleware) > 0) {
            $this->app->pipe(end($middleware), true);
            while (false !== ($foo = prev($middleware))) {
                $this->app->pipe($foo, true);
            }
        }

        return $handler->handle($request);
    }
}
