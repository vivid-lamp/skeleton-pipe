<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use VividLamp\PipeSkeleton\Bootstrap\App;
use VividLamp\PipeSkeleton\Facades\ApiResponse as FacadesApiResponse;
use VividLamp\PipeSkeleton\Facades\Config as FacadesConfig;
use VividLamp\PipeSkeleton\Helper\Env;
use VividLamp\PipeSkeleton\Facades\Env as FacadesEnv;
use VividLamp\PipeSkeleton\Helper\ApiResponse;
use VividLamp\PipeSkeleton\Helper\Config;

class FacadesTest extends TestCase
{

    public function testGetApp()
    {
        $app = new App(__DIR__);
        $this->assertInstanceOf(App::class, $app);
        return $app;
    }

    /** @depends testGetApp */
    public function testEnvFacade($app)
    {
        $app->getContainer()->singleton(Env::class, function () {
            return new Env(__DIR__ . '/resource/.env');
        });
        $name = FacadesEnv::get('name');
        $this->assertEquals($name, 'thisIsName');
    }

    /** @depends testGetApp */
    public function testApiResponseFacade($app)
    {
        $app->getContainer()->singleton(ApiResponse::class);
    
        $this->assertInstanceOf(ResponseInterface::class, FacadesApiResponse::success());
    }

    /** @depends testGetApp */
    public function testConfigFacade($app)
    {
        $app->getContainer()->singleton(Config::class, function () {
            return new Config(__DIR__ . '/resource/');
        });

        $name = FacadesConfig::get('config-data.name');
        $this->assertEquals($name, 'thisIsName');
    }
}
