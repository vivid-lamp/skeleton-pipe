<?php

require_once(__DIR__ . '/../vendor/autoload.php');

$container = new \Illuminate\Container\Container();

$container->instance(\Psr\Container\ContainerInterface::class, $container);

$container->singleton(\Acme\Application::class);

/* 请求处理器 */
$container->singleton(\Relay\Relay::class);

/* 中间件 */
$container->singleton(\Acme\App\Middleware\ExceptionHandlerMiddleware::class);
$container->singleton(\Acme\App\Middleware\InitializeMiddleware::class);
$container->singleton(\Acme\App\Middleware\RouteMiddleware::class);
$container->singleton(\Mezzio\Helper\BodyParams\BodyParamsMiddleware::class);

/* 模板 */
$container->singleton(\League\Plates\Engine::class, function () {
	return new \League\Plates\Engine('view');
});

/* 控制器 */
$container->singleton(\Acme\App\Controller\Index::class);


/* API 响应助手 */
$container->singleton(\Acme\Helper\ApiResponse::class);

/* 响应工厂 */
$container->singleton(\Psr\Http\Message\ResponseFactoryInterface::class, \Laminas\Diactoros\ResponseFactory::class);

return $container;
