<?php

declare(strict_types=1);

namespace VividLamp\PipeSkeleton\Bootstrap;

use OutOfBoundsException;
use SplQueue;
use LogicException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

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
        } elseif (is_object($middleware)) {
            return $middleware->process($request, $this);
        } elseif (is_string($middleware)) {
            $middleware = $this->app->getContainer()->get($middleware);
            return $middleware->process($request, $this);
        } else {
            throw new LogicException();
        }
    }
}
