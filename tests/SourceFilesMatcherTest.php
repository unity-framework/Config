<?php

use e200\MakeAccessible\Make;
use org\bovigo\vfs\vfsStream;
use Unity\Component\Config\Exceptions\UnreadableFolderException;
use Unity\Component\Config\Exceptions\UnsupportedExtensionException;
use Unity\Component\Config\Sources\SourceFilesMatcher;
use Unity\Contracts\Config\Factories\IDriverFactory;
use Unity\Contracts\Config\Factories\ISourceFactory;
use Unity\Tests\Config\TestBase;

class SourceFilesMatcherTest extends TestBase
{
    public function testTryGetDriverUsingExt()
    {
        $driverMock = $this->mockDriverFactory();

        $driverMock
            ->method('makeFromExt')
            ->willReturn(true);

        $sfm = $this->getAccessibleSourceFilesMatcher($driverMock);

        $value = $sfm->tryGetDriverUsingExt('ext');

        $this->assertTrue($value);
    }

    /**
     * Test if `SourceFilesMatcher::tryGetDriverUsingExt()`
     * returns null on with null extensions.
     *
     * @covers SourceFilesMatcher::tryGetDriverUsingExt()
     */
    public function testTryGetDriverUsingExtWithNullExtension()
    {
        $sfm = $this->getAccessibleSourceFilesMatcher();

        $value = $sfm->tryGetDriverUsingExt(null);

        $this->assertNull($value);
    }

    public function testUnsupportedExtensionExceptionTryGetDriverUsingExt()
    {
        $this->expectException(UnsupportedExtensionException::class);

        $driverMock = $this->mockDriverFactory();

        $driverMock
            ->method('makeFromExt')
            ->willReturn(false);

        $sfm = $this->getAccessibleSourceFilesMatcher($driverMock);

        $value = $sfm->tryGetDriverUsingExt('ext');
    }

    public function testGetFilterPattern()
    {
        $sfm = $this->getAccessibleSourceFilesMatcher();

        $this->assertEquals('*.*', $sfm->getFilterPattern(null));

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

        $virtualFolder = vfsStream::setup('root', null, $dir);

        $sfm = $this->getAccessibleSourceFilesMatcher();

        foreach ($jsonFiles as $key => $jsonFile) {
            $expectedFiles[] = $virtualFolder->url().DIRECTORY_SEPARATOR.$key;
        }

        $filterPattern = '*.json';
        $files = $sfm->filterFiles($virtualFolder->url(), $filterPattern);
        $this->assertEquals($expectedFiles, $files);
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

        $this->assertFalse($supportedSourceFiles);
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

        $virtualFolder = vfsStream::setup('root', null, $dir);

        $sourceFactoryMock = $this->mockSourceFactory();

        $sourceFactoryMock
            ->method('makeFromFile')
            ->will($this->onConsecutiveCalls(true, true));

        $sfm = $this->getSourceFilesMatcher($driverFactoryMock, $sourceFactoryMock);

        $supportedSourceFiles = $sfm->match($virtualFolder->url(), null, null);

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

        $virtualFolder = vfsStream::setup('root', null, $dir);

        $sourceFactoryMock = $this->mockSourceFactory();

        $sourceFactoryMock
            ->method('makeFromFile')
            ->will($this->onConsecutiveCalls(true, true));

        $sfm = $this->getSourceFilesMatcher($driverFactoryMock, $sourceFactoryMock);

        $supportedSourceFiles = $sfm->match($virtualFolder->url(), null, 'json');

        $this->assertEquals([true, true], $supportedSourceFiles);
    }

    public function testUnreadableFolderExceptionOnMatchUnreadableFolder()
    {
        $this->expectException(UnreadableFolderException::class);

        $virtualFolder = vfsStream::setup('root', 000);

        $configsPath = $virtualFolder->url().DIRECTORY_SEPARATOR.'configs';

        $sfm = $this->getSourceFilesMatcher();

        $supportedSourceFiles = $sfm->match($configsPath, null, null);
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
