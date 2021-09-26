<?php

declare(strict_types=1);

namespace VividLamp\PipeSkeleton\App\Middleware;

use ErrorException;
use Throwable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use VividLamp\PipeSkeleton\Facades\ApiResponse;

/**
 * 注册和捕获异常
 *
 * @author zhanglihui
 */
class ExceptionHandlerMiddleware implements MiddlewareInterface
{
    public function __construct()
    {
        set_error_handler(function (int $errno, string $err_str, string $err_file = '', int $err_line = 0): void {
            if (error_reporting() & $errno) {
                throw new ErrorException($err_str, 0, $errno, $err_file, $err_line);
            }
        });
    }


    public function exceptionHandle(Throwable $e): ResponseInterface
    {
        // if ($e instanceof ValidateException) {
        //     return ApiResponse::error($e->getMessage());
        // }

        // some logs
        return ApiResponse::error('系统错误');
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        error_reporting(E_ALL);

        try {
            return $handler->handle($request);
        } catch (Throwable $e) {
            return $this->exceptionHandle($e);
        }
    }
}
