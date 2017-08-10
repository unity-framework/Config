<?php

use PHPUnit\Framework\TestCase;
use Unity\Component\Config\Config;
use Unity\Component\Config\Drivers\DriverInterface;

class ConfigTest extends TestCase
{
    function testGet()
    {
        $config = $this->getConfigurableForTest();

        $this->assertEquals('This is the configuration value', $config->get('config'));
        $this->assertTrue(true);
    }

    function getConfigurableForTest()
    {
        $driver = $this->createMock(DriverInterface::class);

        $driver
            ->expects($this->once())
            ->method('get')
            ->willReturn('This is the configuration value');

        return new Config($driver, '');
    }
}
