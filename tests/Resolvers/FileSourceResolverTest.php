<?php

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Unity\Component\Config\DriversRegistry;
use Unity\Component\Config\Matcher\FileSourceMatcher;

class FileSourceResolverTest extends TestCase
{
    public function testGetSearchPattern()
    {
        $fileResolver = $this->getFileResolver();

        $this->assertEquals('database.*', $fileResolver->genMatchPattern('database'));
        $this->assertEquals('database.php', $fileResolver->genMatchPattern('database', 'php'));
    }

    public function testGetSupportedFile()
    {
        $folder = $this->getFolder().DIRECTORY_SEPARATOR;
        $fileResolver = $this->getFileResolverWithConsecutiveDriversRepositoryMockCalls();

        $expectedFile = $folder.'database.php';

        $files = [
            $folder.'database.dll',
            $folder.'database.exe',
            $folder.'database.php',
        ];

        $file = $fileResolver->matchSupportedFiles($files);
        $this->assertEquals($expectedFile, $file);
    }

    public function testGetSupportedFileWithExplicitDriver()
    {
        $folder = $this->getFolder().DIRECTORY_SEPARATOR;
        $driverRepositoryMock = $this->getDriversRepositoryMock();

        $driverRepositoryMock
            ->expects($this->exactly(3))
            ->method('driverHasExt')
            ->will($this->onConsecutiveCalls(false, false, true));

        $fileResolver = new FileSourceMatcher($driverRepositoryMock);

        $expectedFile = $folder.'database.php';

        $files = [
            $folder.'database.dll',
            $folder.'database.exe',
            $folder.'database.php',
        ];

        $driverAlias = 'php';

        $file = $fileResolver->matchSupportedFiles($files, $driverAlias);
        $this->assertEquals($expectedFile, $file);
    }

    /**
     * @cover FileSourceMatcher::glob()
     */
    public function testMatchFilesInFolder()
    {
        $folder = $this->getFolder();
        $fileResolver = $this->getFileResolver();

        $expectedFiles = [
            $folder.DIRECTORY_SEPARATOR.'database.dll',
            $folder.DIRECTORY_SEPARATOR.'database.exe',
            $folder.DIRECTORY_SEPARATOR.'database.php',
        ];

        $files = $fileResolver->matchFilesInFolder($folder, 'database.*');

        $this->assertEquals($expectedFiles, $files);
    }

    public function testGetSourceFileFromFolder()
    {
        $folder = $this->getFolder();
        $fileResolver = $this->getFileResolverWithConsecutiveDriversRepositoryMockCalls();

        $expectedFile = $folder.DIRECTORY_SEPARATOR.'database.php';

        $driver = $fileResolver->matchFromFolder(
            'database',
            $folder,
            null,
            null
        );

        $this->assertEquals($expectedFile, $driver);
    }

    public function testResolve()
    {
        $folder = $this->getFolder();
        $fileResolver = $this->getFileResolverWithConsecutiveDriversRepositoryMockCalls();

        $expectedFile = $folder.DIRECTORY_SEPARATOR.'database.php';

        $fileSource = $fileResolver->match(
            $folder,
            'database',
            null,
            null
        );

        $this->assertEquals($expectedFile, $fileSource->get());
    }

    public function getDriversRepositoryMock()
    {
        return $this->getMockBuilder(DriversRegistry::class)
            ->getMock();
    }

    public function getFolder()
    {
        $dir = [
            'database.dll' => '',
            'database.exe' => '',
            'database.php' => '',
        ];

        $virtualFolder = vfsStream::setup(
            'configs',
            444,
            $dir
        );

        return $virtualFolder->url();
    }

    public function getFileResolver()
    {
        return new FileSourceMatcher($this->getDriversRepositoryMock());
    }

    public function getFileResolverWithConsecutiveDriversRepositoryMockCalls()
    {
        $driversRepo = $this->getDriversRepositoryMock();

        $driversRepo
            ->expects($this->exactly(3))
            ->method('driverSupportsExt')
            ->will($this->onConsecutiveCalls(false, false, true));

        return new FileSourceMatcher($driversRepo);
    }
}
