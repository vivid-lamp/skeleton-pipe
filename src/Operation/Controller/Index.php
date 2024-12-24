<?php

declare(strict_types=1);

namespace VividLamp\PipeSkeleton\Operation\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;
use VividLamp\PipeSkeleton\Helper\ApiResponse;
use function React\Async\await;

class Index
{
    public function index(): ResponseInterface
    {
        return ApiResponse::success(null, ['name' => 'vivid-lamp', 'time' => time()]);
    }

    /**
     * @throws Throwable
     */
    public function show(ServerRequestInterface $request): ResponseInterface
    {
        $timer = \React\Promise\Timer\sleep(1);
        await($timer);
        return ApiResponse::success(null, ['id' => $request->getAttribute('id'), 'name' => 'vivid-lamp', 'time' => time()]);
    }
}
