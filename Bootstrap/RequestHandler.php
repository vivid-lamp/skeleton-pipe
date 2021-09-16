<?php


namespace Framework\Bootstrap;

use Illuminate\Contracts\Container\BindingResolutionException;
use OutOfBoundsException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use SplQueue;

/**
 * 核心中间件调度
 *
 * @author zhanglihui
 */
class RequestHandler implements RequestHandlerInterface
{
    /** @var SplQueue */
    protected $queue;

    /** @var App */
    protected $app;

    public function __construct(SplQueue $queue, App $app)
    {
        $this->queue = $queue;
        $this->app = $app;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws BindingResolutionException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if ($this->queue->isEmpty()) {
            throw new OutOfBoundsException('The middleware queue is empty.');
        }
        $middleware = $this->queue->dequeue();

        if (is_callable($middleware)) {
            return $middleware($request, $this);
        } elseif (is_object($middleware) && $middleware instanceof MiddlewareInterface) {
            return $middleware->process($request, $this);
        } else {
            $middleware = $this->app->getContainer()->make($middleware);
            return $middleware->process($request, $this);
        }
    }
}
