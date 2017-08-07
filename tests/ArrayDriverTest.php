<?php

use PHPUnit\Framework\TestCase;
use Unity\Component\Config\Drivers\File\ArrayDriver;
use Unity\Component\Config\Drivers\File\Exceptions\ConfigNotFoundException;

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
     * Returns a new instance of ArrayDriver
     * @return ArrayDriver
     */
    private function getArrayFileDriverForTest()
    {
        return new ArrayDriver;
    }
}