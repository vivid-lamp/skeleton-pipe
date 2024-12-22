<?php

namespace Acme\App\Controller;

use Acme\Helper\ApiResponse;

class Index
{
	public function __invoke(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
	{
        return ApiResponse::success('hello world', ['name' => 'vivid-lamp', 'time' => time()]);
	}
}