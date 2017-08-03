<?php

use PHPUnit\Framework\TestCase;
use Unity\Component\Configuration\Drivers\ArrayFile\ArrayFile;

class ArrayFileTest extends TestCase
{
    /**
     * Tests `splitValues()` with 2 segments
     *
     * `splitValues()` should return an array containing
     * the root "database" value and the array access key
     * "user"
     */
    function testSplitValuesTwoSegments()
    {
        $driver = $this->getArrayFileDriverForTest();

        $params = $driver->splitValues('database.user');

        $this->assertInternalType('array', $params);
        $this->assertEquals('database', $params['configFileName']);
        $this->assertEquals('user', $params[0]);
    }

    /**
     * Tests `splitValues()` with 3 segments
     *
     * `splitValues()` should return an array containing
     * the root "internationalization" value and the array
     * access keys "languages" and "pt"
     */
    function testSplitValuesThreeSegments()
    {
        $driver = $this->getArrayFileDriverForTest();

        $params = $driver->splitValues('internationalization.languages.pt');

        $this->assertInternalType('array', $params);
        $this->assertEquals('internationalization', $params['configFileName']);
        $this->assertEquals('languages', $params[0]);
        $this->assertEquals('pt', $params[1]);
    }

    /**
     * Tests `testConfigFileName()` with 3 segments
     *
     * `testConfigFileName()` should return the root value
     * from the given array
     */
    function testConfigFileName()
    {
        $driver = $this->getArrayFileDriverForTest();

        $values = [
            'configFileName' => 'database'
        ];

        $configFileName = $driver->getConfigFileName($values);

        $this->assertEquals('database', $configFileName);
    }

    /**
     * Tests `unsetConfigFileName()`
     *
     * `unsetConfigFileName()` should unset the
     * "configFileName" key and value from the
     * given array
     */
    function testUnsetConfigFileName()
    {
        $driver = $this->getArrayFileDriverForTest();

        $values = ['configFileName' => 'database'];

        $driver->unsetConfigFileName($values);

        $this->assertEmpty($values);
    }
    
    /**
     * Tests `getFullPath`
     *
     * `getFullPath` should return the fullPath
     * constructed with the given parameters
     */
    function testGetFullPath()
    {
        $driver = $this->getArrayFileDriverForTest();

        $config = 'database';
        $source = $this->getSourceForTest();

        $fullPath = $driver->getFullPath($config, $source);

        $expected = $source . '/' . $config . '.php';

        $this->assertEquals($expected, $fullPath);
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