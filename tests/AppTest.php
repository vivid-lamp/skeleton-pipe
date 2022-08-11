<?php

use PHPUnit\Framework\TestCase;
use VividLamp\PipeSkeleton\Bootstrap\App;

class AppTest extends TestCase
{

    public function testPaths()
    {
        $basePath = __DIR__;
        $app = new App($basePath);
        $this->assertInstanceOf(App::class, $app);
        $this->assertEquals($app->getBasePath(), $basePath);
    }

    public function testPipe()
    {
        $app = new App(__DIR__);

        $app->pipe(Foo::class);
        $app->pipe(Bar::class, true);

        $that = $this;
        $getQueue = function() use ($that)  {
            $that->assertSame($this->queue->dequeue(), Bar::class);
            $that->assertSame($this->queue->dequeue(), Foo::class);
            $that->assertTrue($this->queue->isEmpty());
        };

        $getQueue->call($app);
    }
}
class Foo{}
class Bar{}