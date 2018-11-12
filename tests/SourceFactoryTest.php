<?php

use e200\MakeAccessible\Make;
use PHPUnit\Framework\TestCase;
use Unity\Component\Config\Factories\SourceFactory;
use Unity\Component\Config\Contracts\Factories\IDriverFactory;
use Unity\Component\Config\Contracts\IContainer;
use Unity\Support\FileInfo;

class SourceFactoryTest extends TestCase
{
    public function testResolveDriverWithFile()
    {
        $driverFactoryMock = $this->createMock(IDriverFactory::class);

        /*
         * SourceFactory will try to resolve the driver.
         */
        $driverFactoryMock
            ->expects($this->once())
            ->method('makeFromFile')
            ->willReturn(true);

        $sourceFactory = $this->getSourceFactory($driverFactoryMock);
        $sourceFactory = Make::accessible($sourceFactory);

        $driver = $sourceFactory->resolveDriver('folder/configs.yml', null, null);

        $this->assertTrue($driver);
    }

    public function testResolveDriverWithDriver()
    {
        $driverFactoryMock = $this->createMock(IDriverFactory::class);

        /*
         * SourceFactory will try to resolve the driver
         */
        $driverFactoryMock
            ->expects($this->once())
            ->method('makeFromAlias')
            ->willReturn(true);

        $sourceFactory = $this->getSourceFactory($driverFactoryMock);
        $sourceFactory = Make::accessible($sourceFactory);

        $driver = $sourceFactory->resolveDriver('', 'php', null);

        $this->assertTrue($driver);
    }

    public function testResolveDriverWithExt()
    {
        $driverFactoryMock = $this->createMock(IDriverFactory::class);

        /*
         * SourceFactory will try to resolve the driver
         */
        $driverFactoryMock
            ->expects($this->once())
            ->method('makeFromExt')
            ->willReturn(true);

        $sourceFactory = $this->getSourceFactory($driverFactoryMock);
        $sourceFactory = Make::accessible($sourceFactory);

        $driver = $sourceFactory->resolveDriver('', null, '');

        $this->assertTrue($driver);
    }

    public function testMakeFromFile()
    {
        $driverFactoryMock = $this->createMock(IDriverFactory::class);

        $driverFactoryMock
            ->expects($this->once())
            ->method('makeFromFile')
            ->willReturn(true);

        $fileInfoMock = $this->createMock(FileInfo::class);

        $fileInfoMock
            ->expects($this->once())
            ->method('name')
            ->willReturn(null);

        $containerMock = $this->createMock(IContainer::class);

        $containerMock
            ->expects($this->once())
            ->method('make')
            ->willReturn(true);

        $sourceFactory = $this->getSourceFactory($driverFactoryMock, $containerMock, $fileInfoMock);

        $source = $sourceFactory->makeFromFile('folder/configs.yml');
    }

    public function testMakeFromFileWithProvidedDriver()
    {
        $driverFactoryMock = $this->createMock(IDriverFactory::class);

        $driverFactoryMock
            ->expects($this->never())
            ->method('makeFromFile');

        $fileInfoMock = $this->createMock(FileInfo::class);

        $fileInfoMock
            ->expects($this->once())
            ->method('name')
            ->willReturn(null);

        $containerMock = $this->createMock(IContainer::class);

        $containerMock
            ->expects($this->once())
            ->method('make')
            ->willReturn(true);

        $sourceFactory = $this->getSourceFactory($driverFactoryMock, $containerMock, $fileInfoMock);

        $source = $sourceFactory->makeFromFile('folder/configs.yml', new stdClass());

        $this->assertTrue($source);
    }

    public function testMakeFromFolder()
    {
        $containerMock = $this->createMock(IContainer::class);

        $containerMock
            ->expects($this->once())
            ->method('make')
            ->willReturn(true);

        $sourceFactory = $this->getSourceFactory(null, $containerMock);

        $source = $sourceFactory->makeFromFolder('folder/');

        $this->assertTrue($source);
    }

    public function getSourceFactory(
        IDriverFactory $driverFactory = null,
        IContainer $container = null,
        FileInfo $fileInfo = null
        ) {
        if (!$driverFactory) {
            $driverFactory = $this->createMock(IDriverFactory::class);
        }

        if (!$container) {
            $container = $this->createMock(IContainer::class);
        }

        if (!$fileInfo) {
            $fileInfo = $this->createMock(FileInfo::class);
        }

        return new SourceFactory($driverFactory, $container, $fileInfo);
    }
}
