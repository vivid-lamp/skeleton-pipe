<?php

declare(strict_types=1);

namespace VividLamp\PipeSkeleton\App\Middleware;

use VividLamp\PipeSkeleton\Bootstrap\App;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use VividLamp\PipeSkeleton\Helper\ApiResponse;
use VividLamp\PipeSkeleton\Helper\Config;
use VividLamp\PipeSkeleton\Helper\Env;

/**
 * 初始化框架
 * @author zhanglihui
 */
class InitializeMiddleware implements MiddlewareInterface
{
    /** @var App  */
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->bindServices();

        return $handler->handle($request);
    }

    protected function bindServices()
    {
        $this->app->getContainer()->singleton(Env::class, function () {
            return new Env($this->app->getBasePath() . '.env');
        });
        $this->app->getContainer()->singleton(Config::class, function () {
            return new Config($this->app->getBasePath() . 'config/');
        });
        $this->app->getContainer()->singleton(ApiResponse::class);
    }
}
