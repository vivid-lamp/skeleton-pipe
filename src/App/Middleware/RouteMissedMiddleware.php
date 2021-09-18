<?php

declare(strict_types=1);


namespace VividLamp\PipeSkeleton\App\Middleware;

use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use FastRoute\Dispatcher;
use LogicException;

class RouteMissedMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $routeStatus = $request->getAttribute('routeResult');
        
        $response = new Response();
        if ($routeStatus === Dispatcher::NOT_FOUND) {
            $response = $response->withStatus(404)->withHeader('Content-Type', 'text/plain');
            $response->getBody()->write('not found.');
        } elseif ($routeStatus === Dispatcher::METHOD_NOT_ALLOWED) {
            $response = $response->withStatus(405)->withHeader('Content-Type', 'text/plain');
            $response->getBody()->write('method no allowed.');
        } else {
            throw new LogicException('routeResult unrecognized.');
        }
        
        return $response;
    }
}
