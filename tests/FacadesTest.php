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
    protected function getApp()
    {
        return new App(__DIR__);
    }

    public function testEnvFacade()
    {
        $app = $this->getApp();
        $app->getContainer()->singleton(Env::class, function () {
            return new Env(__DIR__ . '/resource/.env');
        });
        $name = FacadesEnv::get('name');
        $this->assertEquals($name, 'thisIsName');
    }

    public function testApiResponseFacade()
    {
        $app = $this->getApp();
        $app->getContainer()->singleton(ApiResponse::class);
    
        $this->assertInstanceOf(ResponseInterface::class, FacadesApiResponse::success());
    }

    public function testConfigFacade()
    {
        $app = $this->getApp();
        $app->getContainer()->singleton(Config::class, function () {
            return new Config(__DIR__ . '/resource/');
        });

        $name = FacadesConfig::get('config-data.name');
        $this->assertEquals($name, 'thisIsName');
    }
}
