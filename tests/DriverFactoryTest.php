<?php

use Unity\Support\FileInfo;
use e200\MakeAccessible\Make;
use PHPUnit\Framework\TestCase;
use Unity\Component\Config\Drivers\PhpDriver;
use Unity\Component\Config\Drivers\IniDriver;
use Unity\Component\Config\Drivers\JsonDriver;
use Unity\Component\Config\Drivers\YamlDriver;
use Unity\Component\Config\Factories\DriverFactory;

class DriverFactoryTest extends TestCase
{
    public function testGet()
    {
        $df = $this->getDriverFactory();

        $this->assertEquals(PhpDriver::class, $df->get('php'));
        $this->assertEquals(IniDriver::class, $df->get('ini'));
        $this->assertEquals(JsonDriver::class, $df->get('json'));
        $this->assertEquals(YamlDriver::class, $df->get('yml'));
    }

    public function testHas()
    {
        $df = $this->getDriverFactory();

        $this->assertTrue($df->has('php'));
        $this->assertTrue($df->has('ini'));
        $this->assertTrue($df->has('json'));
        $this->assertTrue($df->has('yml'));
        $this->assertFalse($df->has('xml'));
    }

    public function testGetAll()
    {
        $df = $this->getDriverFactory();
        
        $adf = Make::accessible($df);

        $this->assertEquals($adf->drivers, $df->getAll());
    }

    public function testMakeFromExt()
    {
        $df = $this->getDriverFactory();
        
        $this->assertInstanceOf(PhpDriver::class, $df->makeFromExt('php'));
        $this->assertInstanceOf(PhpDriver::class, $df->makeFromExt('inc'));
        $this->assertInstanceOf(IniDriver::class, $df->makeFromExt('ini'));
        $this->assertInstanceOf(JsonDriver::class, $df->makeFromExt('json'));
        $this->assertInstanceOf(YamlDriver::class, $df->makeFromExt('yml'));
        $this->assertInstanceOf(YamlDriver::class, $df->makeFromExt('yaml'));
    }

    public function testMakeFromUnsupportedExt()
    {
        $df = $this->getDriverFactory();
        
        $this->assertFalse($df->makeFromExt('xml'));
    }

    public function testMakeFromFile()
    {
        $fileInfoMock = $this->createMock(FileInfo::class);

        $fileInfoMock
            ->expects($this->exactly(4))
            ->method('ext')
            ->will($this->onConsecutiveCalls('php', 'ini', 'json', 'yml'));

        $df = $this->getDriverFactory($fileInfoMock);
        
        $this->assertInstanceOf(PhpDriver::class, $df->makeFromFile('folder/config.php'));
        $this->assertInstanceOf(IniDriver::class, $df->makeFromFile('folder/config.ini'));
        $this->assertInstanceOf(JsonDriver::class, $df->makeFromFile('folder/config.json'));
        $this->assertInstanceOf(YamlDriver::class, $df->makeFromFile('folder/config.yml'));
    }

    public function testMakeFromUnsupportedFile()
    {
        $df = $this->getDriverFactory();
        
        $this->assertFalse($df->makeFromFile('folder/config.xml'));
    }
    
    public function testMakeFromAlias()
    {
        $df = $this->getDriverFactory();
        
        $this->assertInstanceOf(PhpDriver::class, $df->makeFromAlias('php'));
        $this->assertInstanceOf(IniDriver::class, $df->makeFromAlias('ini'));
        $this->assertInstanceOf(JsonDriver::class, $df->makeFromAlias('json'));
        $this->assertInstanceOf(YamlDriver::class, $df->makeFromAlias('yml'));
    }

    public function testMakeFromUnknowDriverAlias()
    {
        $df = $this->getDriverFactory();
        
        $this->assertFalse($df->makeFromAlias('xml'));
    }

    public function getDriverFactory(FileInfo $fileInfo = null)
    {
        if (!$fileInfo) {
            $fileInfo = $this->createMock(FileInfo::class);
        }

        return new DriverFactory($fileInfo);
    }
}
