<?php

use Unity\Component\Config\Drivers\File\PhpDriver;
use Unity\Component\Config\Drivers\File\YamlDriver;
use Unity\Component\Config\DriversRegistry;
use PHPUnit\Framework\TestCase;

class DriversRegistryTest extends TestCase
{
    protected $driversRepo;

    protected function setUp()
    {
        parent::setUp();

        $this->driversRepo = new DriversRegistry;
    }

    function testGetDrivers(){
        $drivers = $this->driversRepo->getDrivers();

        $this->assertInternalType('array', $drivers);
        $this->assertGreaterThan(3, count($drivers));
        $this->assertArrayHasKey('php', $drivers);
    }

    function testGetDriverExts(){
        $supportedExts = $this->driversRepo->getDriversExts();

        $this->assertInternalType('array', $supportedExts);
        $this->assertArrayHasKey('php', $supportedExts);
        $this->assertArrayHasKey('ini', $supportedExts);
        $this->assertArrayHasKey('json', $supportedExts);
        $this->assertArrayHasKey('yml', $supportedExts);
    }

    function testHasAlias(){
        $this->assertTrue($this->driversRepo->hasAlias('php'));
        $this->assertFalse($this->driversRepo->hasAlias('exe'));
    }

    function testGetFromAlias(){
        $driver = $this->driversRepo->getFromAlias('php');

        $this->assertEquals(PhpDriver::class, $driver);
    }

    function testHasExtension(){
        $this->assertTrue($this->driversRepo->driverHasExt('php', 'php'));
        $this->assertFalse($this->driversRepo->driverHasExt('php', 'exe'));
    }

    function testGetDriverSupportedExts(){
        $supportedExt = $this->driversRepo->getDriverSupportedExts('php');

        $this->assertEquals(['php', 'inc'], $supportedExt);
    }

    function testGetFromExt() {
        $driver = $this->driversRepo->getFromExt('yaml');

        $this->assertEquals(YamlDriver::class, $driver);
    }

    function testDriverSupportsExt()
    {
        $this->assertTrue($this->driversRepo->driverSupportsExt('yaml'));
    }
}
