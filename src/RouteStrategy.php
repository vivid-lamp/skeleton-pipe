<?php

declare(strict_types=1);

namespace VividLamp\PipeSkeleton;

use Throwable;
use VividLamp\PipeSkeleton\Helper\ApiResponse;
use League\Route\Http\Exception\MethodNotAllowedException;
use League\Route\Http\Exception\NotFoundException;
use League\Route\Strategy\ApplicationStrategy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RouteStrategy extends ApplicationStrategy
{
    public function getMethodNotAllowedDecorator(MethodNotAllowedException $exception): MiddlewareInterface
    {
        return new class () implements MiddlewareInterface {
            public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
            {
                return ApiResponse::result('', 405, 'Method Not Allowed', null, 405);
            }
        };
    }

    public function getNotFoundDecorator(NotFoundException $exception): MiddlewareInterface
    {
        return new class () implements MiddlewareInterface {
            public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
            {
                return ApiResponse::result('', 404, 'Not Found', null, 404);
            }
        };
    }

    public function getThrowableHandler(): MiddlewareInterface
    {
        return new class () implements MiddlewareInterface {
            public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
            {
                try {
                    return $handler->handle($request);
                } catch (Throwable $e) {
                    return ApiResponse::result($e->getMessage(), 500, 'Internal Server Error', null, 500);
                }
            }
        };
    }
}
