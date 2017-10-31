<?php

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Unity\Component\Config\ConfigManager;
use Unity\Component\Config\Exceptions\DriverNotFoundException;
use Unity\Component\Config\Exceptions\UnreadableSourceException;

class FunctionalTest extends TestCase
{
    public function testWithSimpleSource()
    {
        $configManager = $this->getConfigManager();
        $sourceFile = $this->getSourceFile();

        $config = $configManager
            ->setSource($sourceFile)
            ->build();

        $this->assertTrue($config->get('is_working'));
    }

    public function testWithSourceWithoutExtention()
    {
        $this->expectException(DriverNotFoundException::class);

        $configManager = $this->getConfigManager();
        $sourceFile = $this->getSourceFile('db');

        $config = $configManager
            ->setSource($sourceFile)
            ->build();
    }

    public function testWithSourceWithoutExteButSettingTheExtention()
    {
        $configManager = $this->getConfigManager();
        $sourceFile = $this->getSourceFile('db');

        $config = $configManager
            ->setSource($sourceFile)
            ->setExt('json')
            ->build();

        $this->assertTrue($config->get('is_working'));
    }

    public function testWithSourceWithoutExteButSettingTheDriver()
    {
        $configManager = $this->getConfigManager();
        $sourceFile = $this->getSourceFile('db');

        $config = $configManager
            ->setSource($sourceFile)
            ->setDriver('json')
            ->build();

        $this->assertTrue($config->get('is_working'));
    }

    public function testWithNotReadableFileSource()
    {
        $this->expectException(UnreadableSourceException::class);

        $configManager = $this->getConfigManager();
        $sourceFile = $this->getSourceFile('db', '', 000);

        $config = $configManager
            ->setSource($sourceFile)
            ->build();
    }

    public function testWithEmptyFolder()
    {
        $configManager = $this->getConfigManager();
        $sourceFolder = $this->getSourceFolder();

        $config = $configManager
            ->setSource($sourceFolder->url())
            ->build();

        $this->assertCount(0, $config);
        $this->assertEmpty(0, $config);
        $this->assertEquals([], $config->getAll());
    }

    public function testWithFolderContainingConfigFiles()
    {
        $configManager = $this->getConfigManager();

        $dir = [
            'db.json'    => '{ "is_working": true }',
            'cache.json' => '{
                    "can_cache": false,
                    "can_exp_time": 300
                }',
        ];

        $sourceFolder = $this->getSourceFolder($dir);

        $config = $configManager
            ->setSource($sourceFolder->url())
            ->build();

        $this->assertCount(5, $config);
        $this->assertTrue($config['db']['is_working']);
        $this->assertFalse($config['cache']['can_cache']);
        $this->assertEquals(300, $config['cache']['can_exp_time']);
    }

    public function testWithFolderContainingConfigFilesWithDifferentFileExts()
    {
        $configManager = $this->getConfigManager();

        $dir = [
            'db.json'   => '{ "is_working": true }',
            'cache.ini' => 'can_exp_time=300',
        ];

        $sourceFolder = $this->getSourceFolder($dir);

        $config = $configManager
            ->setSource($sourceFolder->url())
            ->build();

        $this->assertCount(4, $config);
        $this->assertTrue($config['db']['is_working']);
        $this->assertEquals(300, $config['cache']['can_exp_time']);
    }

    public function testWithFolderContainingConfigFilesWithoutExt()
    {
        $configManager = $this->getConfigManager();

        $dir = [
            'db'    => '',
            'cache' => '',
        ];

        $sourceFolder = $this->getSourceFolder($dir);

        $config = $configManager
            ->setSource($sourceFolder->url())
            ->build();

        $this->assertCount(0, $config);
    }

    public function testWithFolderContainingConfigFilesWithoutExtSettingDriver()
    {
        $configManager = $this->getConfigManager();

        $dir = [
            'db'    => '{ "is_working": true }',
            'cache' => '{ "can_cache": true }',
        ];

        $sourceFolder = $this->getSourceFolder($dir);

        $config = $configManager
            ->setSource($sourceFolder->url())
            ->setDriver('json')
            ->build();

        $this->assertCount(4, $config);
        $this->assertTrue($config['db']['is_working']);
        $this->assertTrue($config['cache']['can_cache']);
    }

    public function testWithFolderContainingSomeUnreadableConfigFiles()
    {
        $configManager = $this->getConfigManager();

        $sourceFolder = $this->getSourceFolder();

        vfsStream::newFile('db')
            ->at($sourceFolder)
            ->setContent('{ "is_working": true }');

        // This file should be ignored.
        vfsStream::newFile('cache', 000)
            ->at($sourceFolder)
            ->setContent('{ "can_cache": true }');

        $config = $configManager
            ->setSource($sourceFolder->url())
            ->setDriver('json')
            ->build();

        $this->assertCount(2, $config);
        $this->assertArrayNotHasKey('cache', $config);
        $this->assertTrue($config['db']['is_working']);
    }

    public function testWithCacheEnable()
    {
        $configManager = $this->getConfigManager();

        $dir = [
            'configs' => ['db.json' => '{ "is_working": true }'],
            'cache'   => [],
        ];

        $sourceFolder = $this->getSourceFolder($dir, 777);

        $configsFolder = $sourceFolder->url().DIRECTORY_SEPARATOR.'configs';
        $cacheFolder = $sourceFolder->url().DIRECTORY_SEPARATOR.'cache';

        $config = $configManager
            ->setSource($configsFolder)
            ->setupCache($cacheFolder)
            ->build();

        $this->assertCount(2, $config);
        $this->assertTrue($config['db']['is_working']);
    }

    public function getSourceFolder($dir = [], $permissions = null)
    {
        return vfsStream::setup('root', $permissions, $dir);
    }

    public function getSourceFile($filename = 'db.json', $content = '{"is_working": true}', $permissions = null)
    {
        $sourceFolder = $this->getSourceFolder();

        $file = vfsStream::newFile($filename, $permissions)
            ->at($sourceFolder)
            ->setContent($content);

        return $file->url();
    }

    public function getConfigManager()
    {
        return new ConfigManager();
    }
}
