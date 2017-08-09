<?php

use PHPUnit\Framework\TestCase;
use Unity\Component\Config\Drivers\File\IniDriver;

class IniDriverTest extends TestCase
{
    /**
     * @covers IniDriver::getExt()
     * @covers IniDriver::hasExt()
     *
     * `hasExt()`should return true
     * `getExt()` should return the default extension: `ini`
     */
    function testIniIsDefaultExt()
    {
        $driver = new IniDriver;

        $this->assertTrue($driver->hasExt());

        /** ini is the default extension */
        $this->assertEquals('ini', $driver->getExt());
    }

    /**
     * @covers IniDriver::get()
     *
     * Should return the value of database.user = 'root'
     */
    function testGet()
    {
        $drive = new IniDriver;
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
        return __DIR__ . '/ini/';
    }
}
