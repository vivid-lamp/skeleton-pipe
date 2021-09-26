<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use VividLamp\PipeSkeleton\Helper\Config;

class ConfigTest extends TestCase
{
    protected function getEntry()
    {
        return new Config(__DIR__ . '/resource/');
    }

    public function testGetMethod()
    {
        $name = $this->getEntry()->get('config-data.name');

        $this->assertEquals($name, 'thisIsName');
        $this->assertIsArray($this->getEntry()->get('config-data.db'));
    }
}
