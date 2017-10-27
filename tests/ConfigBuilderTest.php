<?php

use e200\MakeAccessible\Make;
use PHPUnit\Framework\TestCase;
use Unity\Component\Config\ConfigBuilder;
use Unity\Contracts\Container\IContainer;

class ConfigBuilderTest extends TestCase
{
    public function testSetSource()
    {
        $configBuilder = $this->getAccessibleConfigBuilder();

        $expectedSource = 'someSource';

        $instance = $configBuilder->getInstance()->setSource($expectedSource);

        $this->assertEquals($expectedSource, $configBuilder->source);
        $this->assertInstanceOf(ConfigBuilder::class, $instance);
    }

    public function testSetExt()
    {
        $configBuilder = $this->getAccessibleConfigBuilder();

        $expectedExt = 'someExt';

        $instance = $configBuilder->getInstance()
            ->setExt($expectedExt);

        $this->assertEquals($expectedExt, $configBuilder->ext);
        $this->assertInstanceOf(ConfigBuilder::class, $instance);
    }

    public function testSetDriver()
    {
        $configBuilder = $this->getAccessibleConfigBuilder();

        $expectedDriver = 'someDriver';

        $instance = $configBuilder->getInstance()
            ->setDriver($expectedDriver);

        $this->assertEquals($expectedDriver, $configBuilder->driver);
        $this->assertInstanceOf(ConfigBuilder::class, $instance);
    }

    public function testSetContainer()
    {
        $configBuilder = $this->getAccessibleConfigBuilder();

        $containerMock = $this->createMock(IContainer::class);

        $instance = $configBuilder->getInstance()
            ->setContainer($containerMock);

        $this->assertEquals($containerMock, $configBuilder->container);
        $this->assertInstanceOf(ConfigBuilder::class, $instance);
    }

    public function testGetContainer()
    {
        $configBuilder = $this->getAccessibleConfigBuilder();

        $configBuilder->container = true;

        $this->assertTrue($configBuilder->getContainer());
    }

    public function testHasContainer()
    {
        $configBuilder = $this->getAccessibleConfigBuilder();

        $this->assertFalse($configBuilder->hasContainer());

        $configBuilder->container = true;
        $this->assertTrue($configBuilder->getContainer());

        $configBuilder->container = false;
        $this->assertFalse($configBuilder->getContainer());
    }

    public function testSetCachePath()
    {
        $configBuilder = $this->getAccessibleConfigBuilder();

        $expectedCachePath = 'someCachePath';

        $instance = $configBuilder->getInstance()
            ->setCachePath($expectedCachePath);

        $this->assertEquals($expectedCachePath, $configBuilder->cachePath);
        $this->assertInstanceOf(ConfigBuilder::class, $instance);
    }

    public function testGetCachePath()
    {
        $configBuilder = $this->getAccessibleConfigBuilder();

        $configBuilder->cachePath = true;

        $this->assertTrue($configBuilder->getCachePath());
    }

    public function testCanCache()
    {
        $configBuilder = $this->getAccessibleConfigBuilder();

        $this->assertFalse($configBuilder->canCache());

        $configBuilder->cachePath = 'cache_path';
        $this->assertTrue($configBuilder->canCache());

        $configBuilder->cachePath = null;
        $this->assertFalse($configBuilder->canCache());
    }

    public function getConfigBuilder()
    {
        return new ConfigBuilder();
    }

    public function getAccessibleConfigBuilder()
    {
        return Make::accessible($this->getConfigBuilder());
    }
}
