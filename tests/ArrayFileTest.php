<?php

use PHPUnit\Framework\TestCase;
use Unity\Component\Configuration\Drivers\ArrayFile;

class ArrayFileTest extends TestCase
{
    function testGetSimpleArray()
    {
        $driver = $this->getArrayDriverForTest();

        $source = $this->getSourceForTest();

        $config = $driver->get('database.user', $source);
        $this->assertEquals('root', $config);

        $config = $driver->get('database.psw', $source);
        $this->assertEquals('1234', $config);

        $config = $driver->get('database.db', $source);
        $this->assertEquals('example', $config);

        $config = $driver->get('database.host', $source);
        $this->assertEquals('127.0.0.1', $config);

        $config = $driver->get('database.cache_queries', $source);
        $this->assertEquals(true, $config);

        $config = $driver->get('database.timeout', $source);
        $this->assertEquals(1000, $config);

    }

    function testGetArrayWithInnerArray()
    {
        $driver = $this->getArrayDriverForTest();
        $source = $this->getSourceForTest();

        $config = $driver->get('internationalization.languages.pt', $source);
        $this->assertEquals(true, $config);
    }

    function getArrayDriverForTest()
    {
        return new ArrayFile;
    }

    function getSourceForTest()
    {
        return __DIR__ . '/configs';
    }
}