<?php


namespace Framework\App\Helper;

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
     * @param string $msg
     * @param mixed $data
     * @return JsonResponse
     */
    public static function success(string $msg = '', $data = null): ResponseInterface
    {
        $result = ['resultCode' => '00000000', 'resultDesc' => $msg];
        if (isset($data)) {
            $result['data'] = $data;
        }
        return new JsonResponse($result, 200);
    }

    /**
     * @param string $msg
     * @param mixed $data
     * @param string $code
     * @return JsonResponse
     */
    public static function error(string $msg = '', $data = null, $code = '999999999'): ResponseInterface
    {
        $result = ['resultCode' => $code, 'resultDesc' => $msg];
        if (isset($data)) {
            $result['data'] = $data;
        }
        return new JsonResponse($result, 200);
    }
}
