<?php

use e200\MakeAccessible\Make;
use PHPUnit\Framework\TestCase;
use Unity\Component\Config\ConfigManager;
use Unity\Component\Config\Contracts\IConfig;
use Unity\Component\Config\Contracts\ILoader;
use Unity\Component\Container\Contracts\IContainer;
use Unity\Component\Config\Contracts\Sources\ISourceCache;
use Unity\Component\Config\Exceptions\InvalidSourceException;

class ConfigManagerTest extends TestCase
{
    public function testSetSource()
    {
        $accessibleInstance = $this->getAccessibleInstance();

        $expectedSource = 'someSource';

        $instance = $accessibleInstance->getClassNameOrInstance()
            ->setSource($expectedSource);

        $this->assertEquals($expectedSource, $accessibleInstance->source);
        $this->assertInstanceOf(ConfigManager::class, $instance);
    }

    public function testSetExt()
    {
        $accessibleInstance = $this->getAccessibleInstance();

        $expectedExt = 'someExt';

        $instance = $accessibleInstance->getClassNameOrInstance()
            ->setExt($expectedExt);

        $this->assertEquals($expectedExt, $accessibleInstance->ext);
        $this->assertInstanceOf(ConfigManager::class, $instance);
    }

    public function testSetExtWithDot()
    {
        $accessibleInstance = $this->getAccessibleInstance();

        $extWithDot = '.json';
        $expectedExt = 'json';

        $instance = $accessibleInstance->getClassNameOrInstance()
            ->setExt($extWithDot);

        $this->assertEquals($expectedExt, $accessibleInstance->ext);
        $this->assertInstanceOf(ConfigManager::class, $instance);
    }

    public function testSetDriver()
    {
        $accessibleInstance = $this->getAccessibleInstance();

        $expectedDriver = 'someDriver';

        $instance = $accessibleInstance->getClassNameOrInstance()
            ->setDriver($expectedDriver);

        $this->assertEquals($expectedDriver, $accessibleInstance->driver);
        $this->assertInstanceOf(ConfigManager::class, $instance);
    }

    public function testSetContainer()
    {
        $accessibleInstance = $this->getAccessibleInstance();

        $containerMock = $this->createMock(IContainer::class);

        $instance = $accessibleInstance->getClassNameOrInstance()
            ->setContainer($containerMock);

        $this->assertEquals($containerMock, $accessibleInstance->container);
        $this->assertInstanceOf(ConfigManager::class, $instance);
    }

    public function testAllowModifications()
    {
        $accessibleInstance = $this->getAccessibleInstance();

        $instance = $accessibleInstance->getClassNameOrInstance();

        $instance->allowModifications(false);
        $this->assertFalse($accessibleInstance->allowModifications);

        $instance->allowModifications(true);
        $this->assertTrue($accessibleInstance->allowModifications);

        $this->assertInstanceOf(ConfigManager::class, $instance);
    }

    public function testHasSource()
    {
        $accessibleInstance = $this->getAccessibleInstance();
        $instance = $accessibleInstance->getClassNameOrInstance();

        $this->assertFalse($instance->hasSource());

        $accessibleInstance->source = 'some_source';
        $this->assertTrue($instance->hasSource());

        $accessibleInstance->source = null;
        $this->assertFalse($instance->hasSource());

        $accessibleInstance->source = '';
        $this->assertFalse($instance->hasSource());
    }

    public function testHasContainer()
    {
        $instance = $this->getAccessibleInstance();

        $this->assertFalse($instance->hasContainer());

        $instance->container = true;
        $this->assertTrue($instance->hasContainer());

        $instance->container = null;
        $this->assertFalse($instance->hasContainer());
    }

    public function testSetupCache()
    {
        $accessibleInstance = $this->getAccessibleInstance();

        $expectedCachePath = 'someCachePath';
        $expectedCacheExpTime = 'someCacheExpTime';
        $expectedallowModifications = false;

        $instance = $accessibleInstance->getClassNameOrInstance()
            ->setupCache(
                $expectedCachePath,
                $expectedCacheExpTime,
                $expectedallowModifications
            );

        $this->assertEquals($expectedCachePath, $accessibleInstance->cachePath);
        $this->assertEquals($expectedCacheExpTime, $accessibleInstance->cacheExpTime);
        $this->assertEquals($expectedallowModifications, $accessibleInstance->allowModifications);
        $this->assertInstanceOf(ConfigManager::class, $instance);
    }

    public function testIsCacheEnable()
    {
        $accessibleInstance = $this->getAccessibleInstance();
        $instance = $accessibleInstance->getClassNameOrInstance();

        $this->assertFalse($instance->isCacheEnabled());

        $accessibleInstance->cachePath = 'cache_path';
        $this->assertTrue($instance->isCacheEnabled());

        $accessibleInstance->cachePath = null;
        $this->assertFalse($instance->isCacheEnabled());
    }

    public function testSetupContainer()
    {
        $accessibleInstance = $this->getAccessibleInstance();

        $accessibleInstance->setupContainer();

        $this->assertInstanceOf(IContainer::class, $accessibleInstance->container);
    }

    /**
     * Tests if `ContainerManager::setupContainer()`
     * doesn't makes a new instance of `IContainer`
     * when we already have one coming from outside.
     *
     * @covers ContainerManager::setupContainer()
     */
    public function testSetupContainerWithContainerAlreadyProvided()
    {
        $accessibleInstance = $this->getAccessibleInstance();

        $containerMock = $this->createMock(IContainer::class);

        $accessibleInstance->container = $containerMock;

        $accessibleInstance->setupContainer();

        $this->assertSame($containerMock, $accessibleInstance->container);
    }

    public function testGetLoader()
    {
        $accessibleInstance = $this->getAccessibleInstance();

        $loaderMock = $this->createMock(ILoader::class);

        $containerMock = $this->createMock(IContainer::class);
        $containerMock
            ->method('get')
            ->willReturn($loaderMock);

        $accessibleInstance->container = $containerMock;

        $this->assertInstanceOf(ILoader::class, $accessibleInstance->getLoader());
    }

    public function testGetSourceCache()
    {
        $accessibleInstance = $this->getAccessibleInstance();

        $sourceCacheMock = $this->createMock(ISourceCache::class);

        $containerMock = $this->createMock(IContainer::class);
        $containerMock
            ->method('make')
            ->willReturn($sourceCacheMock);

        $accessibleInstance->container = $containerMock;

        $this->assertInstanceOf(ISourceCache::class, $accessibleInstance->getSourceCache(null, null, null));
    }

    public function testInvalidSourceExceptionOnBuild()
    {
        $this->expectException(InvalidSourceException::class);

        $instance = $this->getInstance();

        $buildedInstance = $instance->build();
    }

    public function testBuildWithoutCacheEnabled()
    {
        $accessibleInstance = $this->getAccessibleInstance();
        $instance = $accessibleInstance->getClassNameOrInstance();

        $accessibleInstance->source = 'some_source';

        $loaderMock = $this->createMock(ILoader::class);

        $loaderMock
            ->expects($this->once())
            ->method('load');

        $configMock = $this->createMock(IConfig::class);

        $containerMock = $this->createMock(IContainer::class);

        $containerMock
            ->expects($this->once())
            ->method('get')
            ->willReturn($loaderMock);

        $containerMock
            ->expects($this->once())
            ->method('make')
            ->willReturn($configMock);

        $accessibleInstance->container = $containerMock;

        $buildedInstance = $instance->build();

        $this->assertInstanceOf(IConfig::class, $buildedInstance);
    }

    public function testBuildWithCacheEnabledAndHit()
    {
        $accessibleInstance = $this->getAccessibleInstance();
        $instance = $accessibleInstance->getClassNameOrInstance();

        // This prevent the ivalid source exception.
        $accessibleInstance->source = 'some_source';

        // This enable the cache.
        $accessibleInstance->cachePath = 'some_cache_path';

        $loaderMock = $this->createMock(ILoader::class);
        $configMock = $this->createMock(IConfig::class);
        $sourceCache = $this->createMock(ISourceCache::class);

        $sourceCache
            ->expects($this->once())
            ->method('isHit')
            ->willReturn(true);

        $sourceCache
            ->expects($this->once())
            ->method('get');

        $containerMock = $this->createMock(IContainer::class);

        $containerMock
            ->expects($this->once())
            ->method('get')
            ->willReturn($loaderMock);

        $containerMock
            ->expects($this->exactly(2))
            ->method('make')
            ->will($this->onConsecutiveCalls($sourceCache, $configMock));

        $accessibleInstance->container = $containerMock;

        $buildedInstance = $instance->build();

        $this->assertInstanceOf(IConfig::class, $buildedInstance);
    }

    public function testBuildWithCacheEnabledButMissed()
    {
        $accessibleInstance = $this->getAccessibleInstance();
        $instance = $accessibleInstance->getClassNameOrInstance();

        // This prevent the ivalid source exception.
        $accessibleInstance->source = 'some_source';

        // This enable the cache.
        $accessibleInstance->cachePath = 'some_cache_path';

        $loaderMock = $this->createMock(ILoader::class);

        $loaderMock
            ->expects($this->once())
            ->method('load');

        $configMock = $this->createMock(IConfig::class);
        $sourceCache = $this->createMock(ISourceCache::class);

        $sourceCache
            ->expects($this->once())
            ->method('isHit')
            ->willReturn(false);

        $sourceCache
            ->expects($this->once())
            ->method('set');

        $containerMock = $this->createMock(IContainer::class);

        $containerMock
            ->expects($this->once())
            ->method('get')
            ->willReturn($loaderMock);

        $containerMock
            ->expects($this->exactly(2))
            ->method('make')
            ->will($this->onConsecutiveCalls($sourceCache, $configMock));

        $accessibleInstance->container = $containerMock;

        $buildedInstance = $instance->build();

        $this->assertInstanceOf(IConfig::class, $buildedInstance);
    }

    public function getInstance()
    {
        return new ConfigManager();
    }

    public function getAccessibleInstance()
    {
        return Make::accessible($this->getInstance());
    }
}
