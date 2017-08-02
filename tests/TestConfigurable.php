<?php

use PHPUnit\Framework\TestCase;
use Unity\Component\Configurable\Configurable;

class TestConfigurable extends TestCase
{
    function testGet()
    {

    }

    function getConfigurableForTest()
    {
        $driver = $this->createMock(\Unity\Component\Configurable\Drivers\DriverInterface::class);

        return new Configurable($driver, '');
    }
}