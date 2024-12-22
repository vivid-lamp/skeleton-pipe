<?php

declare(strict_types=1);

namespace Acme\Helper;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;

/**
 * 辅助返回 Api 响应对象
 */
class ApiResponse
{
    /**
     * 成功输出
     * @param mixed|null $data 数据
     * @param string|null $msg 消息
     * @param array|null $mixin 自定义字段
     * @return ResponseInterface
     */
    public static function success(?string $msg = null, mixed $data = null, ?array $mixin = null): ResponseInterface
    {
        return static::result($msg, 0, $data, $mixin);
    }

    /**
     * 失败输出
     * @param mixed|null $data 数据
     * @param string|null $msg 消息
     * @param int $code 结果码
     * @param array|null $mixin 自定义字段
     * @return JsonResponse
     */
    public static function error(?string $msg = null, int $code = 1, mixed $data = null, ?array $mixin = null): JsonResponse
    {
        return static::result($msg, $code, $data, $mixin);
    }


    /**
     * 自定义输出
     * @param string|null $msg 消息
     * @param int $code 结果码
     * @param mixed|null $data 数据
     * @param array|null $mixin 自定义字段
     * @param int $status http 响应码
     */
    public static function result(?string $msg = null, int $code = 0, mixed $data = null, ?array $mixin = null, int $status = 200): JsonResponse
    {
        $result = compact('code', 'msg', 'data');

        if (isset($mixin)) {
            $result = array_merge($result, $mixin);
        }

        return new JsonResponse($result, $status, [], JSON_UNESCAPED_UNICODE);
    }
}
