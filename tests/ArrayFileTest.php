<?php

use PHPUnit\Framework\TestCase;
use Unity\Component\Configuration\Drivers\ArrayFile;

class ArrayFileTest extends TestCase
{
    function testGet()
    {
        $driver = $this->getArrayDriverForTest();

        $config = $driver->get('database.user', __DIR__ . '/configs');
        $this->assertEquals('root', $config);

        $config = $driver->get('database.psw', __DIR__ . '/configs');
        $this->assertEquals('1234', $config);

        $config = $driver->get('database.db', __DIR__ . '/configs');
        $this->assertEquals('example', $config);

        $config = $driver->get('database.host', __DIR__ . '/configs');
        $this->assertEquals('127.0.0.1', $config);

        $config = $driver->get('database.cache_queries', __DIR__ . '/configs');
        $this->assertEquals(true, $config);

        $config = $driver->get('database.timeout', __DIR__ . '/configs');
        $this->assertEquals(1000, $config);

    }

    function getArrayDriverForTest()
    {
        return new ArrayFile;
    }
}