<?php

use Unity\Component\Config\Drivers\File\JsonDriver;
use PHPUnit\Framework\TestCase;

class JsonDriverTest extends TestCase
{
    /**
     * @covers JsonDriver::getExt()
     *
     * `getExt()` Should return the default extension: `json`
     */
    function testJsonIsDefaultExt()
    {
        $jsonDriver = $this->getJsonDriverForTest();

        $this->assertEquals('json', $jsonDriver->getExt());
    }

    /**
     * @covers JsonDriver::getJsonAsArray()
     *
     * `getJsonAsArray()` Should return an array containing all
     * the 6 elements in the json file
     */
    function testGetJsonAsArray()
    {
        $jsonDriver = $this->getJsonDriverForTest();
        $source = $this->getSourceForTest();

        $jsonConfigArray = $jsonDriver->getJsonAsArray('database', $source);

        $this->assertInternalType('array', $jsonConfigArray);
        $this->assertCount(6, $jsonConfigArray);
    }

    /**
     * @covers JsonDriver::getConfigArray()
     *
     * `getConfigArray()` should return an array containing all
     * the 6 elements in the json file
     */
    function testGetConfigArray()
    {
        $jsonDriver = $this->getJsonDriverForTest();
        $sources = $this->getSourcesForTest();

        $jsonConfigArray = $jsonDriver->getConfigArray('database', $sources);

        $this->assertInternalType('array', $jsonConfigArray);
        $this->assertCount(6, $jsonConfigArray);
    }

    /**
     * @covers JsonDriver::get()
     *
     * `get` should return the config value referenced
     * by `database.user`
     */
    function testGet()
    {
        $jsonDriver= $this->getJsonDriverForTest();
        $source = $this->getSourceForTest();

        $this->assertEquals('root', $jsonDriver->get('database.user', $source));
    }

    /**
     * Sources must end with a slash "/"
     *
     * @return string
     */
    private function getSourceForTest()
    {
        return __DIR__ . '/jsons/';
    }

    /**
     * Sources must end with a slash "/"
     *
     * @return array
     */
    private function getSourcesForTest()
    {
        return [
            __DIR__ . '/files/',
            __DIR__ . '/jsons/',
            __DIR__ . '/archives/'
        ];
    }

    /**
     * @return JsonDriver
     */
    private function getJsonDriverForTest()
    {
        return new JsonDriver;
    }
}