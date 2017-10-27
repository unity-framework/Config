<?php

use e200\MakeAccessible\Make;
use PHPUnit\Framework\TestCase;
use Unity\Component\Config\Sources\SourceFolder;
use Unity\Contracts\Config\Sources\ISourceFile;
use Unity\Contracts\Config\Sources\ISourceFilesMatcher;

class SourceFolderTest extends TestCase
{
    public function testGetSource()
    {
        $folderSource = $this->getFolderSource(true);

        $this->assertTrue($folderSource->getSource());
    }

    public function testGetSourceFiles()
    {
        $sourceFilesMatcherMock = $this->createMock(ISourceFilesMatcher::class);

        $sourceFilesMatcherMock
            ->expects($this->once())
            ->method('match')
            ->willReturn(true);

        $folderSource = $this->getFolderSource(null, null, null, $sourceFilesMatcherMock);

        $folderSource = Make::accessible($folderSource);

        $this->assertTrue($folderSource->getSourceFiles());
    }

    public function testGetData()
    {
        $fileSourceMock1 = $this->createMock(ISourceFile::class);

        /*
         * Folder source uses the `IFileSource::getKey()`
         * to struct the array data.
         */
        $fileSourceMock1
            ->expects($this->once())
            ->method('getKey')
            ->willReturn('fileSource1');

        /*
         * Folder source uses the `IFileSource::getData()`
         * to get this source data.
         */
        $fileSourceMock1
            ->expects($this->once())
            ->method('getData')
            ->willReturn(true);

        $sourceFilesMatcherMock = $this->createMock(ISourceFilesMatcher::class);

        $sourceFilesMatcherMock
            ->expects($this->once())
            ->method('match')
            ->willReturn([$fileSourceMock1]);

        $folderSource = $this->getFolderSource(null, null, null, $sourceFilesMatcherMock);

        /*
         * The expected value is the structured collected data.
         */
        $this->assertEquals(['fileSource1' => true], $folderSource->getData());
    }

    public function getFolderSource(
        $source = null,
        $driver = null,
        $ext = null,
        ISourceFilesMatcher $sourceFilesMatcher = null
        ) {
        if (!$sourceFilesMatcher) {
            $sourceFilesMatcher = $this->createMock(ISourceFilesMatcher::class);
        }

        return new SourceFolder($source, $driver, $ext, $sourceFilesMatcher);
    }
}
