<?php

declare(strict_types=1);

namespace VividLamp\PipeSkeleton\Operation\Controller;

use Psr\Http\Message\ResponseInterface;
use VividLamp\PipeSkeleton\Helper\ApiResponse;
use Psr\Http\Message\ServerRequestInterface;

class Index
{
    public function index(): ResponseInterface
    {
        return ApiResponse::success(null, ['name' => 'vivid-lamp', 'time' => time()]);
    }

    public function show(ServerRequestInterface $request): ResponseInterface
    {
        return ApiResponse::success(null, ['name' => 'vivid-lamp', 'id' => $request->getAttribute('id'), 'time' => time()]);
    }
}
