<?php

use PHPUnit\Framework\TestCase;
use Unity\Component\Configuration\Drivers\ArrayFile\ArrayFile;

class ArrayFileTest extends TestCase
{
    function testSplitParamsTwoSegments()
    {
        $driver = $this->getArrayFileDriverForTest();

        $params = $driver->splitValues('database.user');

        $this->assertInternalType('array', $params);
        $this->assertEquals('database', $params['configFileName']);
        $this->assertEquals('user', $params[0]);
    }

    function testSplitParamsThreeSegments()
    {
        $driver = $this->getArrayFileDriverForTest();

        $params = $driver->splitValues('internationalization.languages.pt');

        $this->assertInternalType('array', $params);
        $this->assertEquals('internationalization', $params['configFileName']);
        $this->assertEquals('languages', $params[0]);
        $this->assertEquals('pt', $params[1]);
    }

    function testGetSimpleArray()
    {
        $driver = $this->getArrayFileDriverForTest();

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
        $driver = $this->getArrayFileDriverForTest();
        $source = $this->getSourceForTest();

        $config = $driver->get('internationalization.languages.pt', $source);
        $this->assertEquals(true, $config);

        $config = $driver->get('internationalization.languages.en', $source);
        $this->assertEquals(false, $config);

        $config = $driver->get('internationalization.languages.fr', $source);
        $this->assertEquals(false, $config);

        $config = $driver->get('internationalization.languages.es', $source);
        $this->assertEquals(false, $config);
    }

    function getArrayFileDriverForTest()
    {
        return new ArrayFile;
    }

    function getSourceForTest()
    {
        return __DIR__ . '/configs';
    }
}