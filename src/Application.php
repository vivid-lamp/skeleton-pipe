<?php

declare(strict_types=1);

namespace Acme;

use Acme\Server\Http\ServerInterface as HttpServerInterface;
use Psr\Container\ContainerInterface;

/**
 * @author zhanglihui
 */
class Application
{
	private static Application $instance;

	public function __construct(
        protected ContainerInterface $container,
        protected HttpServerInterface $httpServer
    )
	{
		self::$instance = $this;
	}

	public static function get(): Application
	{
		return self::$instance;
	}

	public function container(): ContainerInterface
	{
		return $this->container;
	}

    public function http(): HttpServerInterface
    {
        return $this->httpServer;
    }

}
