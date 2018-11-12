<?php

use e200\MakeAccessible\Make;
use org\bovigo\vfs\vfsStream;
use Unity\Component\Config\Exceptions\DriverNotFoundException;
use Unity\Component\Config\Exceptions\UnsupportedExtensionException;
use Unity\Component\Config\Sources\SourceFilesMatcher;
use Unity\Component\Config\Contracts\Factories\IDriverFactory;
use Unity\Component\Config\Contracts\Factories\ISourceFactory;
use Unity\Tests\Config\TestBase;

class SourceFilesMatcherTest extends TestBase
{
    public function testTryResolveDriverUsingExt()
    {
        $driverMock = $this->mockDriverFactory();

        $driverMock
            ->method('makeFromExt')
            ->willReturn(true);

        $sfm = $this->getAccessibleSourceFilesMatcher($driverMock);

        $value = $sfm->tryResolveDriver(null, 'ext');

        $this->assertTrue($value);
    }

    public function testTryResolveDriverUsingDriver()
    {
        $driverMock = $this->mockDriverFactory();

        $driverMock
            ->method('makeFromAlias')
            ->willReturn(true);

        $sfm = $this->getAccessibleSourceFilesMatcher($driverMock);

        $value = $sfm->tryResolveDriver('driver_alias', null);

        $this->assertTrue($value);
    }

    /**
     * Tests if `SourceFilesMatcher::tryResolveDriver()`
     * returns null with null driver and extensions.
     *
     * @covers SourceFilesMatcher::tryResolveDriver()
     */
    public function testTryResolveDriverUsingExtWithNullValueAndExtension()
    {
        $sfm = $this->getAccessibleSourceFilesMatcher();

        $value = $sfm->tryResolveDriver(null, null);

        $this->assertNull($value);
    }

    public function testUnsupportedExtensionExceptionTryResolveDriverUsingExt()
    {
        $this->expectException(UnsupportedExtensionException::class);

        $driverMock = $this->mockDriverFactory();

        $driverMock
            ->method('makeFromExt')
            ->willReturn(false);

        $sfm = $this->getAccessibleSourceFilesMatcher($driverMock);

        $sfm->tryResolveDriver(null, 'ext');
    }

    public function testDriverNotFoundExceptionTryResolveDriverUsingExt()
    {
        $this->expectException(DriverNotFoundException::class);

        $driverMock = $this->mockDriverFactory();

        $driverMock
            ->method('makeFromAlias')
            ->willReturn(false);

        $sfm = $this->getAccessibleSourceFilesMatcher($driverMock);

        $sfm->tryResolveDriver('some_alias', null);
    }

    public function testGetFilterPattern()
    {
        $sfm = $this->getAccessibleSourceFilesMatcher();

        $this->assertEquals('*', $sfm->getFilterPattern(null));

        $ext = 'yml';
        $this->assertEquals('*.'.$ext, $sfm->getFilterPattern($ext));
    }

    public function testFilterFiles()
    {
        $jsonFiles = [
            'db_connection.json' => '',
            'enviromnent.json'   => '',
        ];

        /**
         * Will test if glob will filter only json files, so,
         * 'cache.php' should not be returned.
         */
        $dir = array_merge($jsonFiles, ['cache.php' => '']);

        $folder = vfsStream::setup('root', null, $dir);

        $sfm = $this->getAccessibleSourceFilesMatcher();

        foreach ($jsonFiles as $key => $jsonFile) {
            $expectedFiles[] = $folder->url().DIRECTORY_SEPARATOR.$key;
        }

        $filterPattern = '*.json';
        $files = $sfm->filterFiles($folder->url(), $filterPattern);
        $this->assertEquals($expectedFiles, $files);
    }

    public function testFilterFilesWithUnreadableFile()
    {
        $folder = vfsStream::setup();

        vfsStream::newFile('db.json')
            ->at($folder);

        vfsStream::newFile('cache.json', 000)
            ->at($folder);

        $sfm = $this->getAccessibleSourceFilesMatcher();

        $files = $sfm->filterFiles($folder->url(), '*');
        $this->assertCount(1, $files);
    }

    public function testGetSourceFiles()
    {
        $sourceFactoryMock = $this->mockSourceFactory();

        $sourceFactoryMock
            ->method('makeFromFile')
            ->willReturn(true);

        $sfm = $this->getAccessibleSourceFilesMatcher(null, $sourceFactoryMock);

        $supportedSourceFiles = $sfm->getSourceFiles([null, null], null);

        $this->assertEquals([true, true], $supportedSourceFiles);
    }

    /**
     * Test if `SourceFilesMatcher::getSourceFiles()` only return supported sources.
     *
     * @covers SourceFilesMatcher::getSourceFiles()
     */
    public function testGetSourceFilesWithSomeUnsupportedSource()
    {
        $sourceFactoryMock = $this->mockSourceFactory();

        $sourceFactoryMock
            ->method('makeFromFile')
            ->will($this->onConsecutiveCalls(true, false));

        $sfm = $this->getAccessibleSourceFilesMatcher(null, $sourceFactoryMock);

        $supportedSourceFiles = $sfm->getSourceFiles([null, null], null);

        $this->assertEquals([true], $supportedSourceFiles);
    }

    /**
     * Test if `SourceFilesMatcher::getSourceFiles()` returns false
     * if no supported source was found.
     *
     * @covers SourceFilesMatcher::getSourceFiles()
     */
    public function testGetSourceFilesWithNoSupportedSource()
    {
        $sourceFactoryMock = $this->mockSourceFactory();

        $sourceFactoryMock
            ->method('makeFromFile')
            ->will($this->onConsecutiveCalls(false, false));

        $sfm = $this->getAccessibleSourceFilesMatcher(null, $sourceFactoryMock);

        $supportedSourceFiles = $sfm->getSourceFiles([null, null], null);

        $this->assertEmpty($supportedSourceFiles);
    }

    public function testMatch()
    {
        $driverFactoryMock = $this->mockDriverFactory();

        $driverFactoryMock
            ->expects($this->never())
            ->method('makeFromExt');

        $dir = [
            'db_connection.json' => '',
            'enviromnent.json'   => '',
        ];

        $folder = vfsStream::setup('root', null, $dir);

        $sourceFactoryMock = $this->mockSourceFactory();

        $sourceFactoryMock
            ->method('makeFromFile')
            ->will($this->onConsecutiveCalls(true, true));

        $sfm = $this->getSourceFilesMatcher($driverFactoryMock, $sourceFactoryMock);

        $supportedSourceFiles = $sfm->match($folder->url(), null, null);

        $this->assertEquals([true, true], $supportedSourceFiles);
    }

    public function testMatchWithExt()
    {
        $driverFactoryMock = $this->mockDriverFactory();

        $driverFactoryMock
            ->expects($this->once())
            ->method('makeFromExt');

        $dir = [
            'db_connection.json' => '',
            'enviromnent.json'   => '',
        ];

        $folder = vfsStream::setup('root', null, $dir);

        $sourceFactoryMock = $this->mockSourceFactory();

        $sourceFactoryMock
            ->method('makeFromFile')
            ->will($this->onConsecutiveCalls(true, true));

        $sfm = $this->getSourceFilesMatcher($driverFactoryMock, $sourceFactoryMock);

        $supportedSourceFiles = $sfm->match($folder->url(), null, 'json');

        $this->assertEquals([true, true], $supportedSourceFiles);
    }

    public function getSourceFilesMatcher(IDriverFactory $driverFactory = null, ISourceFactory $sourceFactory = null)
    {
        if (!$driverFactory) {
            $driverFactory = $this->createMock(IDriverFactory::class);
        }

        if (!$sourceFactory) {
            $sourceFactory = $this->createMock(ISourceFactory::class);
        }

        return new SourceFilesMatcher($driverFactory, $sourceFactory);
    }

    public function getAccessibleSourceFilesMatcher(IDriverFactory $driverFactory = null, ISourceFactory $sourceFactory = null)
    {
        return Make::accessible($this->getSourceFilesMatcher($driverFactory, $sourceFactory));
    }
}
