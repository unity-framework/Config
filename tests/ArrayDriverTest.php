<?php

use PHPUnit\Framework\TestCase;
use Unity\Component\Config\Drivers\File\ArrayDriver;
use Unity\Component\Config\Drivers\File\Exceptions\ConfigFileNotFoundException;

class ArrayDriverTest extends TestCase
{
    /**
     * @covers ArrayDriver::setExt()
     * @covers ArrayDriver::getExt()
     * @covers ArrayDriver::hasExt()
     *
     * Should set and get the config file extension
     */
    function testPhpIsDefaultExt()
    {
        $driver = $this->getArrayFileDriverForTest();

        $this->assertTrue($driver->hasExt());

        /** php is the default extension */
        $this->assertEquals('php', $driver->getExt());
    }

    /**
     * @covers ArrayDriver::requireArrayFile()
     *
     * Should require and return the array
     * with configuration values
     */
    function testRequireArrayFile()
    {
        $driver = $this->getArrayFileDriverForTest();
        $source = $this->getSourceForTest();

        $configArray = $driver->requireArrayFile('database', $source);

        $this->assertInternalType('array', $configArray);
        $this->assertCount(6, $configArray);
    }

    /**
     * @covers ArrayDriver::getConfigArray()
     *
     * Should return the configuration array
     */
    function testGetConfigArray()
    {
        $driver = $this->getArrayFileDriverForTest();
        $source = $this->getSourceForTest();

        $configArray = $driver->getConfigArray('database', $source);

        $this->assertInternalType('array', $configArray);
        $this->assertCount(6, $configArray);
    }

    /**
     * @covers ArrayDriver::getConfigArray()
     *
     * Should return the configuration array
     */
    function testGetConfigArrayWithSourcesArray()
    {
        $driver = $this->getArrayFileDriverForTest();
        $sources = $this->getSourcesForTest();

        $configArray = $driver->getConfigArray('database', $sources);

        $this->assertInternalType('array', $configArray);
        $this->assertCount(6, $configArray);
    }

    /**
     * @covers ArrayDriver::get()
     *
     * Tests if get() throws ConfigFileNotFoundException
     * with non existing configurations
     *
     * In this case get() is searching for
     * NotExistingPath/foo.php
     */
    function testGetNonExistingConfig()
    {
        $this->expectException(ConfigFileNotFoundException::class);

        $driver = $this->getArrayFileDriverForTest();

        $driver->get('foo.bar', 'NotExistingPath/');
    }

    /**
     * @covers ArrayDriver::get()
     *
     * Should return the value of database.user = 'root'
     */
    function testGet()
    {
        $drive = $this->getArrayFileDriverForTest();
        $source = $this->getSourceForTest();

        $this->assertEquals('root', $drive->get('database.user', $source));
    }

    /**
     * Sources must end with a slash "/"
     *
     * @return string
     */
    private function getSourceForTest()
    {
        return __DIR__ . '/arrays/';
    }

    /**
     * Sources must end with a slash "/"
     *
     * @return array
     */
    private function getSourcesForTest()
    {
        return [
            __DIR__ . '/arrays/',
            __DIR__ . '/files/',
            __DIR__ . '/archives/'
        ];
    }

    /**
     * Returns a new instance of ArrayDriver
     * @return ArrayDriver
     */
    private function getArrayFileDriverForTest()
    {
        return new ArrayDriver;
    }
}