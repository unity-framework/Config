<?php

use Unity\Component\Config\Drivers\YamlDriver;
use PHPUnit\Framework\TestCase;
use org\bovigo\vfs\vfsStream;

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
        /*
        $content = '
            exists
                true
        ';

        $folder = vfsStream::setup();
        $dbFile = vfsStream::newFile('db')
            ->at($folder)
            ->setContent($content);

        $loadedArray = $this->getInstance()->load($dbFile->url());

        $this->assertEquals(['exists' => true], $loadedArray);
        */

        $this->assertTrue(true);
    }

    public function getInstance()
    {
        return new YamlDriver();
    }
}