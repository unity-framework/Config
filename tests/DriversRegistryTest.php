<?php

use PHPUnit\Framework\TestCase;
use Unity\Component\Config\Drivers\File\PhpDriver;
use Unity\Component\Config\Drivers\File\YamlDriver;
use Unity\Component\Config\DriversRegistry;

class DriversRegistryTest extends TestCase
{
    protected $driversRepo;

    protected function setUp()
    {
        parent::setUp();

        $this->driversRepo = new DriversRegistry();
    }

    public function testGetDrivers()
    {
        $drivers = $this->driversRepo->getDrivers();

        $this->assertInternalType('array', $drivers);
        $this->assertGreaterThan(3, count($drivers));
        $this->assertArrayHasKey('php', $drivers);
    }

    public function testGetDriverExts()
    {
        $supportedExts = $this->driversRepo->getDriversExts();

        $this->assertInternalType('array', $supportedExts);
        $this->assertArrayHasKey('php', $supportedExts);
        $this->assertArrayHasKey('ini', $supportedExts);
        $this->assertArrayHasKey('json', $supportedExts);
        $this->assertArrayHasKey('yml', $supportedExts);
    }

    public function testHasAlias()
    {
        $this->assertTrue($this->driversRepo->hasAlias('php'));
        $this->assertFalse($this->driversRepo->hasAlias('exe'));
    }

    public function testGetFromAlias()
    {
        $driver = $this->driversRepo->getFromAlias('php');

        $this->assertEquals(PhpDriver::class, $driver);
    }

    public function testHasExtension()
    {
        $this->assertTrue($this->driversRepo->driverHasExt('php', 'php'));
        $this->assertFalse($this->driversRepo->driverHasExt('php', 'exe'));
    }

    public function testGetDriverSupportedExts()
    {
        $supportedExt = $this->driversRepo->getDriverSupportedExts('php');

        $this->assertEquals(['php', 'inc'], $supportedExt);
    }

    public function testGetFromExt()
    {
        $driver = $this->driversRepo->getFromExt('yaml');

        $this->assertEquals(YamlDriver::class, $driver);
    }

    public function testDriverSupportsExt()
    {
        $this->assertTrue($this->driversRepo->driverSupportsExt('yaml'));
    }
}
