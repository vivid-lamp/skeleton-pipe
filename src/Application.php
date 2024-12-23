<?php

declare(strict_types=1);

namespace VividLamp\PipeSkeleton;

use VividLamp\PipeSkeleton\Server\Http\ServerInterface as HttpServerInterface;
use ErrorException;
use Psr\Container\ContainerInterface;

/**
 * @author zhanglihui
 */
class Application
{
    protected static Application $instance;

    /**
     * @throws ErrorException
     */
    public function __construct(
        protected ContainerInterface  $container,
        protected HttpServerInterface $http,
    ) {
        self::$instance = $this;
        $this->setErrorHandler();
    }

    public static function get(): Application
    {
        return static::$instance;
    }

    protected function setErrorHandler(): void
    {
        error_reporting(E_ALL);

        /**
         * @throws ErrorException
         */
        $errorHandler = function (int $errno, string $err_str, string $err_file = '', int $err_line = 0) {
            if (error_reporting() & $errno) {
                throw new ErrorException($err_str, 0, $errno, $err_file, $err_line);
            }
        };

        set_error_handler($errorHandler);
    }

    public function container(): ContainerInterface
    {
        return $this->container;
    }

    public function http(): HttpServerInterface
    {
        return $this->http;
    }
}
