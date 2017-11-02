<?php

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Unity\Component\Config\Drivers\YamlDriver;

class YamlDriverTest extends TestCase
{
    public function testExtensions()
    {
        $extensions = $this->getInstance()->extensions();

        $this->count(2, $extensions);
        $this->assertTrue(in_array('yml', $extensions));
        $this->assertTrue(in_array('yaml', $extensions));
    }

    public function testLoad()
    {
        $content = 'exists: true';

        $folder = vfsStream::setup();
        $dbFile = vfsStream::newFile('db')
            ->at($folder)
            ->setContent($content);

        $loadedArray = $this->getInstance()->load($dbFile->url());

        $this->assertEquals(['exists' => true], $loadedArray);
    }

    public function getInstance()
    {
        return new YamlDriver();
    }
}
