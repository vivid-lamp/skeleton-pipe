<?php

declare(strict_types=1);

namespace Acme\App\Middleware;

use League\Plates\Engine;
use think\facade\Db;
use Acme\Application;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Acme\Helper\ApiResponse;

/**
 * 初始化框架
 * @author zhanglihui
 */
class InitializeMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->bindServices();

        return $handler->handle($request);
    }

    protected function bindServices()
    {
		// todo 连接池？去除 facade？
		Db::setConfig(require 'config/database.php');
    }
}
