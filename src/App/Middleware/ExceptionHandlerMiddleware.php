<?php

declare(strict_types=1);

namespace Acme\App\Middleware;

use Acme\Helper\ApiResponse;
use ErrorException;
use Throwable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * 注册和捕获异常
 *
 * @author zhanglihui
 */
class ExceptionHandlerMiddleware implements MiddlewareInterface
{
    public function exceptionHandle(Throwable $e): ResponseInterface
    {
        return ApiResponse::error($e->getMessage());
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        error_reporting(E_ALL);

        /**  @throws ErrorException */
        $errorHandler = function (int $errno, string $err_str, string $err_file = '', int $err_line = 0) {
            if (error_reporting() & $errno) {
                throw new ErrorException($err_str, 0, $errno, $err_file, $err_line);
            }
        };

        set_error_handler($errorHandler);

        try {
            return $handler->handle($request);
        } catch (Throwable $e) {
            return $this->exceptionHandle($e);
        }
    }
}
