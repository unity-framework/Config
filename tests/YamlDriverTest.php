<?php

use PHPUnit\Framework\TestCase;
use Unity\Component\Config\Drivers\File\YamlDriver;

class YamlDriverTest extends TestCase
{
    /**
     * @covers IniDriver::getExt()
     * @covers IniDriver::hasExt()
     *
     * `hasExt()`should return true
     * `getExt()` should return the default extension: `ini`
     */
    function testYmlIsDefaultExt()
    {
        $driver = new YamlDriver;

        $this->assertTrue($driver->hasExt());

        /** ini is the default extension */
        $this->assertEquals('yml', $driver->getExt());
    }

    /**
     * @covers IniDriver::get()
     *
     * Should return the value of database.user = 'root'
     */
    function testGet()
    {
        $drive = new YamlDriver;
        $source = $this->getSourceForTest();

        $this->assertEquals('root', $drive->get('database.user', $source));
    }

    /**
     * @return string
     */
    private function getSourceForTest()
    {
        return __DIR__ . '/yml';
    }
}
