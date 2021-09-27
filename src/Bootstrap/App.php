<?php

declare(strict_types=1);

namespace VividLamp\PipeSkeleton\Bootstrap;

use Illuminate\Container\Container;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\ServerRequestFactory;
use SplQueue;

/**
 * @author zhanglihui
 */
class App
{
    /** @var SplQueue */
    protected $queue;

    /** @var Container  */
    protected $container;

    /** @var string */
    protected $basePath;

    /** @var string */
    protected $runtimePath;

    /** @var string */
    protected $cachePath;

    /** @var App */
    private static $instance;

    /**
     * @param string $basePath      框架目录
     * @param string $runtimePath   runtime 目录
     * @param string $cachePath     缓存目录
     */
    public function __construct(string $basePath, ?string $runtimePath = null, ?string $cachePath = null)
    {
        $this->basePath = $basePath;
        $this->runtimePath = $runtimePath ?? $basePath . 'runtime/';
        $this->cachePath = $cachePath ?? $this->runtimePath . 'cache/';

        $this->queue = new SplQueue();

        static::$instance = $this;

        $this->initialize();
    }

    public function initialize()
    {
        $this->container = new Container();
        Container::setInstance($this->container);

        $this->container->instance(App::class, $this);
    }

    /** @return App */
    public static function getInstance()
    {
        return self::$instance;
    }

    /**
     * 添加中间件
     * middleware 类名或对象
     * @param mixed $middleware 中间件类、对象，或其他可执行对象
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
        $request = $request ?? ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);
        $this->runWithRequest($request);
    }

    public function runWithRequest(ServerRequestInterface $request)
    {
        $requestHandler = new RequestHandler($this->queue, $this);
        $response = $requestHandler->handle($request);
        $this->emit($response);
    }

    public function getContainer(): Container
    {
        return $this->container;
    }

    public function emit(ResponseInterface $response)
    {
        http_response_code($response->getStatusCode());
        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value), false);
            }
        }
        echo $response->getBody();
    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }

    public function getRuntimePath(): string
    {
        return $this->runtimePath;
    }

    public function getCachePath(): string
    {
        return $this->cachePath;
    }
}
