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
     * @param array         $header     响应头
     * @param array         $mixin      自定义字段
     * @return Response
     */
    public function success($msg = '', $data = '', array $mixin = [], array $header = []): ResponseInterface
    {
        return $this->result($msg, 0, $data, $mixin, $header);
    }

    /**
     * 失败输出
     * @param mixed         $data       数据
     * @param string        $msg        消息
     * @param int           $code       结果码
     * @param array         $header     响应头
     * @param array         $mixin      自定义字段
     * @return Response
     */
    public function error($msg = '', $code = 400, $data = '', array $mixin = [], array $header = []): ResponseInterface
    {
        return $this->result($msg, $code, $data, $mixin, $header);
    }


    public static function result($msg = '', int $code = 0, $data = '', array $mixin = [], array $headers = []): ResponseInterface
    {
        $result   = [
            'code' => $code,
            'msg'  => $msg,
            'time' => time(),
            'data' => $data,
        ];

        if (! empty($mixin)) {
            $result = array_merge($result, $mixin);
        }

        return new JsonResponse($result, 200, $headers);
    }
}
