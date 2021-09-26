<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use VividLamp\PipeSkeleton\Helper\Env;

class EnvTest extends TestCase
{
    protected function getEntry()
    {
        return new Env(__DIR__ . '/resource/.env');
    }

    public function testGetMethod()
    {
        $env = $this->getEntry();
        $name = $env->get('name');
        $this->assertEquals($name, 'thisIsName');
    }

    public function testAllMethod()
    {
        $env = $this->getEntry();
        $content = $env->all();
        $this->assertIsArray($content);
        $this->assertArrayHasKey('DB', $content);
        $this->assertEquals($content['DB']['database'], 'aDataBase');
    }
}
