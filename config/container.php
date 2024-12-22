<?php

$container = new \Illuminate\Container\Container();
$container->instance(\Psr\Container\ContainerInterface::class, $container);

$container->singleton(\Acme\Application::class);

/* http 服务 */
$container->singleton(
    \Acme\Server\Http\ServerInterface::class,
    \Acme\Server\Http\Fpm::class,
);

/* 中间件 */
$container->singleton(\Acme\App\Middleware\ExceptionHandlerMiddleware::class);
$container->singleton(\Mezzio\Helper\BodyParams\BodyParamsMiddleware::class);

/* 控制器 */
$container->singleton(\Acme\App\Controller\Index::class);

return $container;
