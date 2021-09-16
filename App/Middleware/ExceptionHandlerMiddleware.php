<?php

namespace Framework\App\Middleware;

use ErrorException;
use Framework\App\Helper\ApiResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\ValidateException;
use Throwable;

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
        if ($e instanceof ValidateException) {
            return ApiResponse::error($e->getMessage());
        } elseif ($e instanceof ModelNotFoundException || $e instanceof DataNotFoundException) {
            return ApiResponse::error('参数错误：数据未找到');
        } else {
            // some log
            return ApiResponse::error('系统错误');
        }
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (Throwable $e) {
            return $this->exceptionHandle($e);
        }
    }
}
