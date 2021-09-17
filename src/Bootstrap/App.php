<?php

namespace VividLamp\PipeSkeleton\Bootstrap;

use Illuminate\Container\Container;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\ServerRequestFactory;
use SplQueue;

/**
 * Class App
 * @author zhanglihui
 */
class App
{
    /**  @var SplQueue */
    protected $queue;

    /** @var Container  */
    protected $container;

    /** @var string */
    protected $basePath;

    /** @var App */
    private static $instance;

    public function __construct($basePath)
    {
        $this->basePath = $basePath;

        $this->queue = new SplQueue();

        $this->container = new Container();
        Container::setInstance($this->container);
        $this->container->instance(App::class, $this);

        static::$instance = $this;
    }

    /** @return App */
    public static function getInstance()
    {
        return self::$instance;
    }

    /**
     * 添加中间件
     * middleware 类名或对象
     * @param mixed $middleware 中间件类、对象，或其可执行对象
     * @param bool  $atHead   是否在队头添加
     */
    public function pipe($middleware, $atHead = false)
    {
        if ($atHead) {
            $this->queue->unshift($middleware);
        } else {
            $this->queue->enqueue($middleware);
        }
    }

    public function run(?ServerRequestInterface $request = null)
    {
        $request = $request ?? ServerRequestFactory::fromGlobals(
            $_SERVER,
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES
        );
        $this->runWithRequest($request);
    }

    public function runWithRequest(ServerRequestInterface $request)
    {
        $requestHandler = new RequestHandler($this->queue, $this);
        $response = $requestHandler->handle($request);
        $this->emit($response);
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function emit(ResponseInterface $response)
    {
        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value), false);
            }
        }
        echo $response->getBody();
    }

    public function getBasePath()
    {
        return $this->basePath;
    }

    public function getRuntimePath()
    {
        return $this->getBasePath() . 'runtime/';
    }

    public function getCachePath()
    {
        return $this->getRuntimePath() . 'cache/';
    }

}
