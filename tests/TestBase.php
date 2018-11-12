<?php

namespace Unity\Tests\Config;

use PHPUnit\Framework\TestCase;
use Unity\Component\Config\Contracts\Drivers\IDriver;
use Unity\Component\Config\Contracts\Factories\IDriverFactory;
use Unity\Component\Config\Contracts\Factories\ISourceFactory;
use Unity\Component\Config\Contracts\Sources\ISourceFilesMatcher;
use Unity\Component\Config\Contracts\IContainer;
use Unity\Contracts\Notator\INotator;
use Unity\Support\FileInfo;

class TestBase extends TestCase
{
    public function mockContainer()
    {
        return $this->createMock(IContainer::class);
    }

    public function mockDriverFactory()
    {
        return $this->createMock(IDriverFactory::class);
    }

    public function mockSourceFactory()
    {
        return $this->createMock(ISourceFactory::class);
    }

    public function mockFileInfo()
    {
        return $this->createMock(FileInfo::class);
    }

    public function mockSourceFilesMatcher()
    {
        return $this->createMock(ISourceFilesMatcher::class);
    }

    public function mockDriver()
    {
        return $this->createMock(IDriver::class);
    }

    public function mockNotator()
    {
        return $this->createMock(INotator::class);
    }
}
