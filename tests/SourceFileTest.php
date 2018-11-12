<?php

use PHPUnit\Framework\TestCase;
use Unity\Component\Config\Sources\SourceFile;
use Unity\Component\Config\Contracts\Drivers\IDriver;

class SourceFileTest extends TestCase
{
    public function testGetKey()
    {
        $fileSource = $this->getFileSource(true, null);

        $this->assertTrue($fileSource->getKey());
    }

    public function testGetSource()
    {
        $fileSource = $this->getFileSource(null, true);

        $this->assertTrue($fileSource->getSource());
    }

    public function testGetData()
    {
        $driverMock = $this->createMock(IDriver::class);

        $driverMock
            ->expects($this->once())
            ->method('load')
            ->willReturn([true]);

        $fileSource = $this->getFileSource(null, null, $driverMock);

        $this->assertEquals([true], $fileSource->getData());
    }

    public function getFileSource($key, $source, IDriver $driver = null)
    {
        if (!$driver) {
            $driver = $this->createMock(IDriver::class);
        }

        return new SourceFile($key, $source, $driver);
    }
}
