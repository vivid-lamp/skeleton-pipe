<?php

namespace Acme\Server\Http;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;

class React implements ServerInterface
{
    public function __construct(
        protected ContainerInterface $container,
    )
    {
    }

    public function serve(): void
    {
        $handler = (require 'config/route.php')($this->container);

        $http = new \React\Http\HttpServer(function (ServerRequestInterface $request) use ($handler) {
            echo $request->getUri()->getPath(), PHP_EOL;
            return $handler->handle($request);
        });

        $socket = new \React\Socket\SocketServer('0.0.0.0:8081');
        $http->listen($socket);
    }
}