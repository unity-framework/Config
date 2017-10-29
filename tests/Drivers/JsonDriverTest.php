<?php

use Unity\Component\Config\Drivers\JsonDriver;
use PHPUnit\Framework\TestCase;
use org\bovigo\vfs\vfsStream;

class JsonDriverTest extends TestCase
{
    public function testExtensions()
    {
        $extensions = $this->getInstance()->extensions();

        $this->count(1, $extensions);        
        $this->assertTrue(in_array('json', $extensions));
    }

    public function testLoad()
    {
        $content = '{ "exists": true }';

        $folder = vfsStream::setup();
        $dbFile = vfsStream::newFile('db')
            ->at($folder)
            ->setContent($content);

        $loadedArray = $this->getInstance()->load($dbFile->url());

        $this->assertEquals(['exists' => true], $loadedArray);
    }

    public function getInstance()
    {
        return new JsonDriver();
    }
}