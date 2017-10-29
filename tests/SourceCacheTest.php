<?php

use e200\MakeAccessible\Make;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Unity\Component\Config\Sources\SourceCache;

class SourceCacheTest extends TestCase
{
    /**
     * Test if `SourceCache::getSourceHash()`
     * returns the md5 hash of the `$source`.
     */
    public function testGetSourceHash()
    {
        $source = 'config.json';
        $expected = md5($source);

        $instance = $this->getAccessibleInstance($source);

        $this->assertEquals($expected, $instance->getSourceHash());
    }

    /**
     * Test if `SourceCache::getCacheName()`
     * returns the concatenation of `$cachePath`
     * with a backslash and the md5 hash of the
     * `$sourcePath`.
     */
    public function testGetCacheName()
    {
        $sourcePath = 'source/path';
        $cachePath = 'cache/path';

        $expected = $cachePath.DIRECTORY_SEPARATOR.md5($sourcePath);

        $instance = $this->getAccessibleInstance($sourcePath, $cachePath);

        $this->assertEquals($expected, $instance->getCacheName());
    }

    /**
     * Test if `SourceCache::cacheModTime()`
     * returns the last modification time of
     * the cache.
     */
    public function testCacheModTime()
    {
        $expectedModTime = 1996;

        $cacheFolder = vfsStream::setup()->lastModified($expectedModTime);

        $instance = $this->getAccessibleInstance(null, $cacheFolder->url());
        $this->assertEquals($expectedModTime, $instance->CacheModTime());
    }

    /**
     * Test if `SourceCache::sourceModTime()`
     * returns the last modification time of
     * the source.
     */
    public function testSourceModTime()
    {
        $expectedModTime = 1996;

        // Testing with a folder.
        $cacheFolder = vfsStream::setup()->lastModified($expectedModTime);

        $instance = $this->getAccessibleInstance($cacheFolder->url(), null);
        $this->assertEquals($expectedModTime, $instance->sourceModTime());


        $expectedModTime = 2090;

        // Testing with a file.
        $sourceFile = vfsStream::newFile('config.json')
            ->at($cacheFolder)
            ->lastModified($expectedModTime);

        $instance = $this->getAccessibleInstance($sourceFile->url(), null);
        $this->assertEquals($expectedModTime, $instance->sourceModTime());
    }

    /**
     * Test if `SourceCache::hasChangesOnSource()`
     * returns false when the cache data is updated.
     * 
     * The cache data is updated when the source
     * modification time is less than the cache
     * modification time.
     *
     * @covers SourceCache::hasChangesOnSource()
     */
    public function testHasChangesOnSourceWhenCacheDataIsUpdated()
    {
        $cacheFolder = vfsStream::setup();

        $cacheModTime = 1996;
        $sourceModTime = $cacheModTime - 1;

        $sourceFile = vfsStream::newFile('config.json')
            ->at($cacheFolder)
            ->lastModified($sourceModTime);

        $cacheFolder->lastModified($cacheModTime);

        $instance = $this->getAccessibleInstance(
            $sourceFile->url(),
            $cacheFolder->url()
        );

        $this->assertFalse($instance->hasChangesOnSource());
    }

    /**
     * Test if `SourceCache::hasChangesOnSource()`
     * returns true when the cache data is outdated.
     * 
     * The cache data is outdated when the source
     * modification time is greater than the cache
     * modification time.
     *
     * @covers SourceCache::hasChangesOnSource()
     */
    public function testHasChangesOnSourceWhenCacheDataIsOutdated()
    {
        $cacheFolder = vfsStream::setup();

        $sourceModTime = 1996;        
        $cacheModTime = $sourceModTime - 1;

        $sourceFile = vfsStream::newFile('config.json')
            ->at($cacheFolder)
            ->lastModified($sourceModTime);

        $cacheFolder->lastModified($cacheModTime);

        $instance = $this->getAccessibleInstance(
            $sourceFile->url(),
            $cacheFolder->url()
        );

        $this->assertTrue($instance->hasChangesOnSource());
    }

    public function testPrependExpTime()
    {
        $time = time();

        $expected = $time . PHP_EOL . 'data';

        $instance = $this->getAccessibleInstance(null, null, '0 seconds');

        $this->assertEquals($expected, $instance->prependExpTime('data'));
    }

    /**
     * Tests if `SourceCache::prependExpTime()`
     * prepends the `SourceCache::CACHE_FOREVER_SYMBOL`
     * to the cached file expiration time on the first
     * line.
     * 
     * @covers SourceCache::prependExpTime()
     */
    public function testPrependExpTimeForever()
    {
        $serializedData = 'serializedData';

        $expected = SourceCache::CACHE_FOREVER_SYMBOL . PHP_EOL . $serializedData;

        $instance = $this->getAccessibleInstance(null, null, 'forever');

        $this->assertEquals($expected, $instance->prependExpTime($serializedData));
    }

    public function testGetSet()
    {
        $dir = [
            'cache'   => [],
            'configs' => [],
        ];

        $cacheFolder = vfsStream::setup('root', null, $dir);

        $sourcePath = $cacheFolder->url().DIRECTORY_SEPARATOR.'configs';
        $cachePath = $cacheFolder->url().DIRECTORY_SEPARATOR.'cache';

        $expectedCachedFileName = $cachePath.DIRECTORY_SEPARATOR.md5($sourcePath);

        $instance = $this->getInstance($sourcePath, $cachePath, '1 hour');

        $expectedData = [
            'timeout'   => 300,
            'can_cache' => false,
            'users'     => [
                'e200' => [
                    'admin' => false,
                ],
                'd3a' => [
                    'admin' => true,
                ],
            ],
        ];

        $instance->set($expectedData);

        $this->assertFileExists($expectedCachedFileName);
        $this->assertEquals($expectedData, $instance->get());
    }

    /**
     * Tests if `SourceCache::getInstanceExpTime()`
     * extracts the first line of the cached files.
     */
    public function testGetSourceCacheExpTime()
    {
        $expectedExpTime = time();
        $cachedContent = $expectedExpTime.PHP_EOL.'some content';

        $cacheFolder = vfsStream::setup();

        $sourceFile = vfsStream::newFile('file')
            ->at($cacheFolder)
            ->setContent($cachedContent);

        /**
         * We need this because `SourceCache::class`
         * will hash the source path.
         */
        $cachedFile = md5($sourceFile->url());
            
        vfsStream::newFile($cachedFile)
            ->at($cacheFolder)
            ->setContent($cachedContent);
            
        $instance = $this->getAccessibleInstance(
            $sourceFile->url(),
            $cacheFolder->url()
        );

        $this->assertEquals($expectedExpTime, $instance->getSourceCacheExpTime());
    }

    /**
     * Tests if `SourceCache::getSourceCacheExpTime()`
     * extracts the first line and returns true if
     * the first line is a string containing the text
     * 'forever'.
     * 
     * @covers SourceCache::getInstanceExpTime()
     */
    public function testGetSourceCacheExpTimeForever()
    {
        $cacheFolder = vfsStream::setup();
        $sourceFile  = vfsStream::newFile('file')
            ->at($cacheFolder);
            
        $cachedFile = md5($sourceFile->url());
                        
        vfsStream::newFile($cachedFile)
            ->at($cacheFolder)
            ->setContent(SourceCache::CACHE_FOREVER_SYMBOL.PHP_EOL.'some content');

        $instance = $this->getAccessibleInstance($sourceFile->url(), $cacheFolder->url());

        $this->assertTrue($instance->getSourceCacheExpTime());
    }

    /**
     * Tests if `SourceCache::getSourceCacheExpTime()`
     * extracts the first line and returns false if
     * something is wrong with the first line of
     * the cache file.
     * 
     * @covers SourceCache::getInstanceExpTime()
     */
    public function testGetSourceCacheExpTimeInvalid()
    {
        $cacheFolder = vfsStream::setup();

        $sourceFile = vfsStream::newFile('file')
            ->at($cacheFolder);
            
        $cachedFile = md5($sourceFile->url());
                        
        vfsStream::newFile($cachedFile)
            ->at($cacheFolder);

        $instance = $this->getAccessibleInstance(
            $sourceFile->url(),
            $cacheFolder->url()
        );

        $this->assertFalse($instance->getSourceCacheExpTime());
    }

    /**
     * Tests if `SourceCache::isHit()`
     * returns `true` if `!isExpired()`,
     * `!hasChangesOnSource()` and the
     * `$cacheFile` exists.
     */
    public function testIsHit()
    {
        $expTime = time() - 1000;

        $cacheFolder = vfsStream::setup();
        $sourceFile  = vfsStream::newFile('file')
            ->at($cacheFolder);

        $cachedName = md5($sourceFile->url());
        $cachedContent = $expTime.PHP_EOL.'some content';

        $cacheFile = vfsStream::newFile($cachedName)
            ->at($cacheFolder)
            ->setContent($cachedContent);

        $instance = $this->getInstance(
            $sourceFile->url(),
            $cacheFolder->url()
        );

        $this->assertTrue($instance->isHit());
    }

    /**
     * Tests if `SourceCache::isHit()`
     * returns `false` if isExpired()`,
     * `!hasChangesOnSource()` and the
     * `$cacheFile` exists.
     */
    public function testIsHitWithExpiredCachedData()
    {
        $expTime = time() + 1000;

        $cacheFolder = vfsStream::setup();
        $sourceFile  = vfsStream::newFile('file')
            ->at($cacheFolder);

        $cachedName = md5($sourceFile->url());
        $cachedContent = $expTime.PHP_EOL.'some content';

        $cacheFile = vfsStream::newFile($cachedName)
            ->at($cacheFolder)
            ->setContent($cachedContent);

        $instance = $this->getInstance(
            $sourceFile->url(),
            $cacheFolder->url()
        );

        $this->assertFalse($instance->isHit());
    }

    /**
     * Tests if `SourceCache::isHit()`
     * returns `true` if `!isExpired()`,
     * `!hasChangesOnSource()` and the
     * `$cacheFile` doesn't exists.
     */
    public function testIsHitWithNotExistingSourceFile()
    {
        $expTime = time() - 1000;

        $cacheFolder = vfsStream::setup();
        $sourceFile  = vfsStream::newFile('file')
            ->at($cacheFolder);

        $cachedName = md5($sourceFile->url());
        $cachedContent = $expTime.PHP_EOL.'some content';

        $cacheFile = vfsStream::newFile($cachedName)
            ->at($cacheFolder)
            ->setContent($cachedContent);

        $instance = $this->getInstance(
            $sourceFile->url(),
            $cacheFolder->url()
        );

        unlink($cacheFile->url());

        $this->assertFalse($instance->isHit());
    }

    /**
     * Tests if `SourceCache::isMiss()`
     * returns `true` if `isExpired()`,
     * `!hasChangesOnSource()` and the
     * `$cacheFile` exists.
     */
    public function testIsMiss()
    {
        $expTime = time() + 1000;

        $cacheFolder = vfsStream::setup();
        $sourceFile  = vfsStream::newFile('file')
            ->at($cacheFolder);

        $cachedName = md5($sourceFile->url());
        $cachedContent = $expTime.PHP_EOL.'some content';

        $cacheFile = vfsStream::newFile($cachedName)
            ->at($cacheFolder)
            ->setContent($cachedContent);

        $instance = $this->getInstance(
            $sourceFile->url(),
            $cacheFolder->url()
        );

        $this->assertTrue($instance->isMiss());
    }

    /**
     * Tests if `SourceCache::isMiss()`
     * returns `true` if `!isExpired()`,
     * `hasChangesOnSource()` and the
     * `$cacheFile` exists.
     */
    public function testIsMissWithExpiredCachedData()
    {
        $expTime = time() - 1000;
        $sourceChangeTime = time() + 1000;

        $cacheFolder = vfsStream::setup();
        $sourceFile  = vfsStream::newFile('file')
            ->at($cacheFolder)
            // Will tell to `SourceCache::hasChangesOnSource()`
            // that the source was changed.
            ->lastModified($sourceChangeTime);

        $cachedName = md5($sourceFile->url());
        $cachedContent = $expTime.PHP_EOL.'some content';

        $cacheFile = vfsStream::newFile($cachedName)
            ->at($cacheFolder)
            ->setContent($cachedContent);

        $instance = $this->getInstance(
            $sourceFile->url(),
            $cacheFolder->url()
        );

        $this->assertTrue($instance->isMiss());
    }

    /**
     * Tests if `SourceCache::isMiss()`
     * returns `true` if `!isExpired()`,
     * `!hasChangesOnSource()` and the
     * `$cacheFile` doesn't exists.
     */
    public function testIsMissWithNotExistingSourceFile()
    {
        $expTime = time() - 1000;

        $cacheFolder = vfsStream::setup();
        $sourceFile  = vfsStream::newFile('file')
            ->at($cacheFolder);

        $cachedName = md5($sourceFile->url());
        $cachedContent = $expTime.PHP_EOL.'some content';

        $cacheFile = vfsStream::newFile($cachedName)
            ->at($cacheFolder)
            ->setContent($cachedContent);

        $instance = $this->getInstance(
            $sourceFile->url(),
            $cacheFolder->url()
        );

        unlink($cacheFile->url());

        $this->assertTrue($instance->isMiss());
    }

    public function testConvertoToTimestamp()
    {
        $instance = $this->getAccessibleInstance();

        $this->assertGreaterThan(strtotime('1 hour'), $instance->convertoToTimestamp('2 hours'));
        $this->assertLessThan(strtotime('1 hour'), $instance->convertoToTimestamp('-2 hours'));
    }

    public function getInstance($source = null, $cachePath = null, $cacheExpTime = null)
    {
        return new SourceCache($source, $cachePath, $cacheExpTime);
    }

    public function getAccessibleInstance($source = null, $cachePath = null, $cacheExpTime = null)
    {
        return Make::accessible($this->getInstance($source, $cachePath, $cacheExpTime));
    }
}
