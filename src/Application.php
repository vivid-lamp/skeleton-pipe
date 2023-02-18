<?php

declare(strict_types=1);

namespace Acme;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\ServerRequestFactory;
use React\Http\Message\Response;
use SplQueue;

/**
 * @author zhanglihui
 */
class Application
{
	/** @var SplQueue 中间件列表 */
	protected SplQueue $queue;

	protected ContainerInterface $container;

	private static Application $instance;

	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
		$this->queue = new SplQueue();
		self::$instance = $this;
	}

	public static function get(): Application
	{
		return self::$instance;
	}

	/**
	 * 添加中间件
	 * middleware 类名或对象
	 * @param mixed $middleware 中间件类、对象，或其他可执行对象
	 * @param bool $atHead 是否在队头添加
	 */
	public function pipe(mixed $middleware, bool $atHead = false): void
	{
		if ($atHead) {
			$this->queue->unshift($middleware);
		} else {
			$this->queue->enqueue($middleware);
		}
	}

	public function run(?ServerRequestInterface $request = null): void
	{
		$request = $request ?? ServerRequestFactory::fromGlobals();

		$middlewareResolver = function ($middleware) {
			return $this->container->get($middleware);
		};

		$handler = new \Relay\Relay($this->queue, $middlewareResolver);
		$response = $handler->handle($request);

		$this->emit($response);
	}

	public function runWithReact(): void
	{
		$middlewareResolver = function ($middleware) {
			$this->container->bind($middleware, null, true);
		};
		$handler = new \Relay\Relay($this->queue, $middlewareResolver);
		$http = new \React\Http\HttpServer(function (ServerRequestInterface $request) use ($handler) {

			echo $request->getMethod(), ' ', $request->getUri()->getPath(), PHP_EOL;

			$path = $request->getUri()->getPath();
			if (preg_match("/^[\s\S]*\.(pdf|png|jpeg|jpg|docx|xlsx|pjpg|svg|js|css|ttf)$/", $path)) {
				$response = new Response();
				$file = getcwd() . '/public' . $path;
				$response->getBody()->write(file_get_contents($file));
				return $response;
			}

			return $handler->handle($request);
		});

		$socket = new \React\Socket\SocketServer('0.0.0.0:8000');
		$http->listen($socket);
	}

	public function getContainer(): ContainerInterface
	{
		return $this->container;
	}

	public function emit(ResponseInterface $response): void
	{
		http_response_code($response->getStatusCode());

		foreach ($response->getHeaders() as $name => $values) {
			foreach ($values as $value) {
				header(sprintf('%s: %s', $name, $value), false);
			}
		}

		echo $response->getBody();
	}
}
