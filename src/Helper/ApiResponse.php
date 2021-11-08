<?php

declare(strict_types=1);


namespace VividLamp\PipeSkeleton\Helper;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;

/**
 * 辅助返回 Api 响应对象
 *
 * @author zhanglihui
 */
class ApiResponse
{

    /**
     * 成功输出
     * @param mixed         $data       数据
     * @param string        $msg        消息
     * @param array         $headers     响应头
     * @param array         $mixin      自定义字段
     * @return ResponseInterface
     */
    public function success(?string $msg = null, $data = null, ?array $mixin = null, ?array $headers = null): ResponseInterface
    {
        return $this->result($msg, 0, $data, $mixin, $headers);
    }

    /**
     * 失败输出
     * @param mixed         $data       数据
     * @param string        $msg        消息
     * @param int           $code       结果码
     * @param array         $headers    响应头
     * @param array         $mixin      自定义字段
     * @return ResponseInterface
     */
    public function error(?string $msg = null, int $code = 1, $data = null, ?array $mixin = null, ?array $headers = null): ResponseInterface
    {
        return $this->result($msg, $code, $data, $mixin, $headers);
    }


    /**
     * 自定义输出
     * @param string        $msg        消息
     * @param int           $code       结果码
     * @param mixed         $data       数据
     * @param array         $mixin      自定义字段
     * @param array         $headers    响应头
     */
    public function result(?string $msg = null, int $code = 0, $data = null, ?array $mixin = null, ?array $headers = null): ResponseInterface
    {
        $result   = [
            'code' => $code,
            'msg'  => $msg,
            'time' => time(),
            'data' => $data,
        ];

        if (isset($mixin)) {
            $result = array_merge($result, $mixin);
        }

        return new JsonResponse($result, 200, $headers ?? []);
    }
}
