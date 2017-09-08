<?php

use PHPUnit\Framework\TestCase;

use Unity\Component\Config\Drivers\File\JsonDriver;
use Unity\Component\Config\Drivers\File\YamlDriver;

use Unity\Component\Config\Drivers\DriverFactory;
use Unity\Component\Config\DriversRegistry;

class DriverFactoryTest extends TestCase
{
    protected $factory;

    function testMakeFromAlias(){
        $driversRepoMock = $this->mockDriversRepository();

        $driversRepoMock
            ->expects($this->once())
            ->method('getFromAlias')
            ->willReturn(YamlDriver::class);

        $factory = new DriverFactory($driversRepoMock);

        $driver = $factory->makeFromAlias('yaml');

        $this->assertInstanceOf(YamlDriver::class, $driver);
    }

    function testGetFromExt(){
        $driversRepoMock = $this->mockDriversRepository();

        $driversRepoMock
            ->expects($this->once())
            ->method('getFromExt')
            ->willReturn(JsonDriver::class);

        $factory = new DriverFactory($driversRepoMock);

        $driver = $factory->makeFromExt('inc');

        $this->assertInstanceOf(JsonDriver::class, $driver);
    }

    function mockDriversRepository()
    {
        return $this->getMockBuilder(DriversRegistry::class)
        ->getMock();
    }
}
