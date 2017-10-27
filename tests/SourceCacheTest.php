<?php

use org\bovigo\vfs\vfsStream;
use e200\MakeAccessible\Make;
use PHPUnit\Framework\TestCase;
use Unity\Component\Config\Sources\SourceCache;

class SourceCacheTest extends TestCase
{
    public function testGetHash()
    {
        $value = 'config.json';
        $expected = md5($value);
        
        $sourceCache = $this->getAccessibleSourceCache($value, null);
        
        $this->assertEquals($expected, $sourceCache->getHash());
    }

    public function testGetCacheFileName()
    {
        $sourcePath = 'source/path';
        $cachePath = 'cache/path';

        $expected = $cachePath . DIRECTORY_SEPARATOR . md5($sourcePath);

        $sourceCache = $this->getAccessibleSourceCache($sourcePath, $cachePath);

        $this->assertEquals($expected, $sourceCache->getCacheFileName());
    }

    public function testLastCacheTime()
    {
        // Testing with a folder.        
        $virtualFolder = vfsStream::setup()->lastModified(12345);

        $sourceCache = $this->getAccessibleSourceCache(null, $virtualFolder->url());
        $this->assertEquals(12345, $sourceCache->lastCacheTime());

        // Testing with a folder.
        $file = vfsStream::newFile('config.json')
            ->at($virtualFolder)
            ->lastModified(54321);

        $sourceCache = $this->getAccessibleSourceCache(null, $file->url());
        $this->assertEquals(54321, $sourceCache->lastCacheTime());
    }

    public function testLastSourceModTime()
    {
        // Testing with a folder.        
        $virtualFolder = vfsStream::setup()->lastModified(12345);

        $sourceCache = $this->getAccessibleSourceCache($virtualFolder->url(), null);
        $this->assertEquals(12345, $sourceCache->lastSourceModTime());
        
        // Testing with a folder.
        $file = vfsStream::newFile('config.json')
            ->at($virtualFolder)
            ->lastModified(54321);

        $sourceCache = $this->getAccessibleSourceCache($file->url(), null);
        $this->assertEquals(54321, $sourceCache->lastSourceModTime());
    }

    public function testHasChanges()
    {
        // Source time < cache time
        $virtualFolder = vfsStream::setup();
        
        $file = vfsStream::newFile('config.json')
            ->at($virtualFolder)
            ->lastModified(99);
        $virtualFolder->lastModified(100);
        $sourceCache = $this->getSourceCache($file->url(), $virtualFolder->url());

        $this->assertFalse($sourceCache->hasChanges());

        // Source time == cache time
        $virtualFolder = vfsStream::setup();

        $file = vfsStream::newFile('config.json')
            ->at($virtualFolder)
            ->lastModified(100);
        $virtualFolder->lastModified(100);
        $sourceCache = $this->getSourceCache($file->url(), $virtualFolder->url());
            
        $this->assertFalse($sourceCache->hasChanges());

        // Source time > cache time
        $virtualFolder = vfsStream::setup();
        
        $file = vfsStream::newFile('config.json')
            ->at($virtualFolder)
            ->lastModified(101);
        $sourceCache = $this->getSourceCache($file->url(), $virtualFolder->url());
        $virtualFolder->lastModified(100);        

        $this->assertTrue($sourceCache->hasChanges());
    }

    public function testGetSet()
    {
        $dir = [
            'cache' => [],
            'configs' => []
        ];

        $virtualFolder = vfsStream::setup('root', null, $dir);
        
        $sourcePath = $virtualFolder->url() . DIRECTORY_SEPARATOR . 'configs';
        $cachePath = $virtualFolder->url() . DIRECTORY_SEPARATOR . 'cache';

        $expectedCachedFileName = $cachePath . DIRECTORY_SEPARATOR . md5($sourcePath);
        
        $sourceCache = $this->getSourceCache($sourcePath, $cachePath);

        $expectedData = [
            'timeout' => 300,
            'can_cache' => false,
            'users' => [
                'e200' => [
                    'admin' => false
                ],
                'd3a' => [
                    'admin' => true
                ]
            ]
        ];

        $sourceCache->set($expectedData);

        $this->assertFileExists($expectedCachedFileName);
        $this->assertEquals($expectedData, $sourceCache->get());
    }

    public function testGetExpTime()
    {
        $expectedExpTime = 12345;

        $virtualFolder = vfsStream::setup();

        $file = vfsStream::newFile('file')
            ->at($virtualFolder)
            ->setContent($expectedExpTime . "\nCache data");
        
        $sourceCache = $this->getAccessibleSourceCache();

        $this->assertEquals($expectedExpTime, $sourceCache->getExpTime($file->url()));
    }

    public function testIsHit()
    {
        $virtualFolder = vfsStream::setup();     

        $file = vfsStream::newFile('file')->at($virtualFolder);

        $cachedFilename = md5($file->url());
        
        $cacheFile = vfsStream::newFile($cachedFilename)
            ->at($virtualFolder)
            ->setContent(time() - 1000 . "\nCache data");
            
        $sourceCache = $this->getSourceCache($file->url(), $virtualFolder->url());

        $this->assertFalse($sourceCache->isHit());

        $cacheFile->setContent(time() + 1000 . "\nCache data");

        $this->assertTrue($sourceCache->isHit());        
    }

    public function testGetInvalidExpTime()
    {
        $virtualFolder = vfsStream::setup();

        $file = vfsStream::newFile('file')
            ->at($virtualFolder);
        
        $sourceCache = $this->getAccessibleSourceCache();

        $this->assertFalse($sourceCache->getExpTime($file->url()));
    }

    public function getSourceCache($source = null, $cachePath = null, $cacheExpTime = null)
    {
        return new SourceCache($source, $cachePath, $cacheExpTime);
    }

    public function getAccessibleSourceCache($source = null, $cachePath = null, $cacheExpTime = null)
    {
        return Make::accessible($this->getSourceCache($source, $cachePath, $cacheExpTime));
    }
}
