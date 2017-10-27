<?php

namespace Unity\Tests\Config;

use Unity\Support\FileInfo;
use PHPUnit\Framework\TestCase;
use Unity\Contracts\Container\IContainer;
use Unity\Contracts\Config\Drivers\IDriver;
use Unity\Contracts\Config\Factories\IDriverFactory;
use Unity\Contracts\Config\Factories\ISourceFactory;
use Unity\Contracts\Config\Sources\ISourceFilesMatcher;

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
}