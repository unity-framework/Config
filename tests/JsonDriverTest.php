<?php

use Unity\Component\Config\Drivers\File\JsonDriver;
use PHPUnit\Framework\TestCase;

class JsonDriverTest extends TestCase
{
    /**
     * @covers JsonDriver::hasExt()
     * @covers JsonDriver::getExt()
     *
     * `hasExt()`should return true
     * `getExt()` should return the default extension: `json`
     */
    function testJsonIsDefaultExt()
    {
        $jsonDriver = new JsonDriver;

        $this->assertTrue($jsonDriver->hasExt());

        $this->assertEquals('json', $jsonDriver->getExt());
    }

    /**
     * @covers JsonDriver::get()
     *
     * Should return the value of database.user = 'root'
     */
    function testGet()
    {
        $drive = new JsonDriver;
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
        return __DIR__ . '/json/';
    }
}