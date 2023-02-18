<?php

declare(strict_types=1);

namespace Acme\App\Middleware;

use League\Route\Http\Exception\HttpExceptionInterface;
use League\Route\Router;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * 路由调度
 * @author zhanglihui
 */
class RouteMiddleware implements MiddlewareInterface
{
	protected Router $router;

	protected ResponseFactoryInterface $responseFactory;

	public function __construct(ContainerInterface $container, ResponseFactoryInterface $responseFactory)
	{
		$this->responseFactory = $responseFactory;
		$this->router = (require 'config/route.php') ($container);
	}

	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
		try {
			return $this->router->handle($request);
		} catch (HttpExceptionInterface $e) {
			$response = $this->responseFactory->createResponse($e->getStatusCode(), $e->getMessage());
			foreach ($e->getHeaders() as $name => $value) {
				$response = $response->withHeader($name, $value);
			}
			return $response;
		}
    }
}
