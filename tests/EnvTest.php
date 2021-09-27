<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use VividLamp\PipeSkeleton\Helper\Env;

class EnvTest extends TestCase
{
    public function testConstruct()
    {
        $entry = new Env(__DIR__ . '/resource/.env');
        $this->assertInstanceOf(Env::class, $entry);
        return $entry;
    }

    /** @depends testConstruct */
    public function testGet($entry)
    {
        $name = $entry->get('name');
        $this->assertEquals($name, 'thisIsName');
    }

    /** @depends testConstruct */
    public function testAll($entry)
    {
        $content = $entry->all();
        $this->assertIsArray($content);
        $this->assertArrayHasKey('DB', $content);
        $this->assertEquals($content['DB']['database'], 'aDataBase');
    }
}
