<?php

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Unity\Component\Config\Loader;
use Unity\Contracts\Config\Sources\ISource;
use Unity\Contracts\Config\Factories\ISourceFactory;
use Unity\Component\Config\Exceptions\DriverNotFoundException;
use Unity\Component\Config\Exceptions\UnreadableSourceException;

class LoaderTest extends TestCase
{
    public function testLoadFile()
    {
        $sourceMock = $this->createMock(ISource::class);

        $sourceMock
            ->expects($this->once())
            ->method('getData')
            ->willReturn(true);

        $sourceFactoryMock = $this->createMock(ISourceFactory::class);

        $sourceFactoryMock
            ->expects($this->once())
            ->method('makeFromFile')
            ->willReturn($sourceMock);

        $folder = vfsStream::setup();

        $file = vfsStream::newFile('config.ini')->at($folder);

        $loader = $this->getLoader($sourceFactoryMock);

        $this->assertTrue($loader->load($file->url(), null, null));
    }

    public function testLoadFolder()
    {
        $sourceMock = $this->createMock(ISource::class);

        $sourceMock
            ->expects($this->once())
            ->method('getData')
            ->willReturn(true);

        $sourceFactoryMock = $this->createMock(ISourceFactory::class);

        $sourceFactoryMock
            ->expects($this->once())
            ->method('makeFromFolder')
            ->willReturn($sourceMock);

        $folder = vfsStream::setup();

        $loader = $this->getLoader($sourceFactoryMock);

        $this->assertTrue($loader->load($folder->url(), null, null));
    }

    public function testUnreadableSourceExceptionOnLoadNotReabableFile()
    {
        $this->expectException(UnreadableSourceException::class);
        
        $folder = vfsStream::setup();

        $file = vfsStream::newFile('config.ini', 000)->at($folder);

        $loader = $this->getLoader();

        $loader->load($file->url(), null, null);
    }

    public function testDriverNotFoundExceptionOnLoadUnsupportedFile()
    {
        $this->expectException(DriverNotFoundException::class);

        $sourceFactoryMock = $this->createMock(ISourceFactory::class);

        $sourceFactoryMock
            ->expects($this->once())
            ->method('makeFromFile')
            ->willReturn(false);

        $folder = vfsStream::setup();

        $file = vfsStream::newFile('config.ini')->at($folder);

        $loader = $this->getLoader($sourceFactoryMock);

        $this->assertTrue($loader->load($file->url(), null, null));
    }

    public function getLoader(ISourceFactory $sourceFactory = null)
    {
        if (!$sourceFactory) {
            $sourceFactory = $this->createMock(ISourceFactory::class);
        }

        return new Loader($sourceFactory);
    }
}
