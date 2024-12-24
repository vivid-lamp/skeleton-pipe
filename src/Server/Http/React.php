<?php

declare(strict_types=1);

namespace VividLamp\PipeSkeleton\Server\Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use React\Http\HttpServer;
use React\Socket\SocketServer;
use function React\Async\async;

class React implements ServerInterface
{
    public function __construct(
        protected RequestHandlerInterface $requestHandler,
        protected LoggerInterface         $logger,
        protected string                  $host = '0.0.0.0',
        protected int                     $port = 8080
    ) {
    }

    public function serve(): void
    {
        $http = new HttpServer(async(function(ServerRequestInterface $request) {
            $response = $this->requestHandler->handle($request);
            $this->logger->info('{method} {status} {uri}', [
                'method' => $request->getMethod(),
                'uri'    => (string)$request->getUri(),
                'status' => $response->getStatusCode(),
            ]);
            return $response;
        }));

        $socket = new SocketServer("tcp://{$this->host}:{$this->port}");
        $http->listen($socket);
    }
}
