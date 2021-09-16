<?php

namespace Framework\App\Middleware;

use Framework\Bootstrap\App;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;


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
        $this->initDb();
        return $handler->handle($request);
    }


    /**
     * 初始化 think-db
     */
    protected function initDb()
    {
        
    }
}
