<?php

declare(strict_types=1);

$container = new \Illuminate\Container\Container();
$container->instance(\Psr\Container\ContainerInterface::class, $container);

/* 应用 */
$container->singleton(\VividLamp\PipeSkeleton\Application::class);

/* 环境变量 */
$container->singleton(\VividLamp\PipeSkeleton\Helper\Env::class, function () {
    return new \VividLamp\PipeSkeleton\Helper\Env('./.env');
});

/* 路由 */
/* 路由：策略 */
$container->singleton(\League\Route\Strategy\StrategyInterface::class, \VividLamp\PipeSkeleton\RouteStrategy::class);
/* 路由：请求处理器 */
$container->singleton(\Psr\Http\Server\RequestHandlerInterface::class, function () use ($container) {
    return $container->call(require 'config/route.php');
});

/* http 服务 */
$container->singleton(\VividLamp\PipeSkeleton\Server\Http\ServerInterface::class, function () use ($container) {
    $config = $container->call(require 'config/http-server.php');
    return $container->make('\VividLamp\PipeSkeleton\Server\Http\\' . ucfirst($config['driver']), $config[$config['driver']]);
});

/* 中间件 */
$container->singleton(\Mezzio\Helper\BodyParams\BodyParamsMiddleware::class);

/* 控制器 */
$container->singleton(\VividLamp\PipeSkeleton\Operation\Controller\Index::class);

/* 日志 */
$container->singleton(Psr\Log\LoggerInterface::class, function () {
    $loop = \React\EventLoop\Loop::get();
    return \WyriHaximus\React\PSR3\Stdio\StdioLogger::create($loop)->withNewLine(true);
});

return $container;
