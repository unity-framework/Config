<?php

use PHPUnit\Framework\TestCase;
use Unity\Component\Config\Drivers\File\ArrayDriver;
use Unity\Component\Config\Drivers\File\Exceptions\ConfigNotFoundException;

class ArrayDriverTest extends TestCase
{
    /**
     * @covers ArrayDriver::getExt()
     * @covers ArrayDriver::hasExt()
     *
     * `hasExt()`should return true
     * `getExt()` should return the default extension: `php`
     */
    function testPhpIsDefaultExt()
    {
        $driver = new ArrayDriver;

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
        $drive = new ArrayDriver;
        $source = $this->getSourceForTest();

        $this->assertEquals('root', $drive->get('database.user', $source));
    }

    /**
     * @return string
     */
    private function getSourceForTest()
    {
        return __DIR__ . '/array';
    }
}