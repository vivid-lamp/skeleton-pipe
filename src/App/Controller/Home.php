<?php

declare(strict_types=1);

namespace VividLamp\PipeSkeleton\App\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use VividLamp\PipeSkeleton\Facades\ApiResponse;

class Home
{
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        return ApiResponse::success('this message is from home.', $request->getAttributes());
    }
}
