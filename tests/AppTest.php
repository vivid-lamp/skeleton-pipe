<?php

use PHPUnit\Framework\TestCase;
use VividLamp\PipeSkeleton\Bootstrap\App;

class AppTest extends TestCase
{

    public function basePathProvider() {
        return [
            [__DIR__ . '/../public/', null, null],
            [__DIR__ . '/../public/', __DIR__ . '/../public/runtime/', null],
            [__DIR__ . '/../public/', null, __DIR__ . '/../public/runtime/cache/'],
            [__DIR__ . '/../public/', __DIR__ . '/../public/runtime/', __DIR__ . '/../public/runtime/cache/'],
        ];
    }

    /** @dataProvider  basePathProvider */
    public function testPaths($basePath, $runtimePath, $cachePath)
    {
        $app = new App($basePath, $runtimePath, $cachePath);
        $this->assertInstanceOf(App::class, $app);
        $this->assertEquals($app->getBasePath(), $basePath);

        if ($runtimePath === null and $cachePath === null) {
            $this->assertEquals($app->getRuntimePath(), $app->getBasePath() . 'runtime/');
            $this->assertEquals($app->getCachePath(), $app->getRuntimePath() . 'cache/');
        } elseif ($cachePath === null) {
            $this->assertEquals($app->getRuntimePath(), $runtimePath);
            $this->assertEquals($app->getCachePath(), $app->getRuntimePath() . 'cache/');
        } elseif ($runtimePath === null) {
            $this->assertEquals($app->getRuntimePath(), $app->getBasePath() . 'runtime/');
            $this->assertEquals($app->getCachePath(), $cachePath);
        } else {
            $this->assertEquals($app->getRuntimePath(), $runtimePath);
            $this->assertEquals($app->getCachePath(), $cachePath);
        }
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