<?php

use PHPUnit\Framework\TestCase;
use Unity\Component\Config\Drivers\ArrayFile\ArrayFile;

class ArrayFileTest extends TestCase
{
    /**
     * @covers ArrayFile::resolve()
     */
    function testResolve()
    {
        $drive = $this->getArrayFileDriverForTest();
        $source = $this->getSourceForTest();

        $this->assertEquals('root', $drive->resolve('database.user', $source));
    }

    function testSetGetExt()
    {
        $driver = $this->getArrayFileDriverForTest();

        $this->assertEquals('php', $driver->getExt());
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