<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use VividLamp\PipeSkeleton\Helper\Config;

class ConfigTest extends TestCase
{
    public function testConstruct()
    {
        $entry = new Config(__DIR__ . '/resource/');
        $this->assertInstanceOf(Config::class, $entry);
        return $entry;
    }

    /** @depends testConstruct */
    public function testGet(Config $entry)
    {
        $name = $entry->get('config-data.name');
        $this->assertEquals($name, 'thisIsName');

        $this->assertIsArray($entry->get('config-data.db'));
        $this->assertArrayHasKey('database', $entry->get('config-data.db'));
        $this->assertEquals($entry->get('config-data.db')['database'], 'demo');

        $this->assertEquals($entry->get('config-data.db.database-null'), null);
    }
}
