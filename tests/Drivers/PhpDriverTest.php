<?php

use Unity\Component\Config\Drivers\PhpDriver;
use PHPUnit\Framework\TestCase;
use org\bovigo\vfs\vfsStream;

class PhpDriverTest extends TestCase
{
    public function testExtensions()
    {
        $extensions = $this->getInstance()->extensions();

        $this->count(2, $extensions);
        $this->assertTrue(in_array('php', $extensions));
        $this->assertTrue(in_array('inc', $extensions));
    }

    public function testLoad()
    {
        $content = "
        <?php

        return [
            'exists' => true
        ];
        ";

        $folder = vfsStream::setup();
        $dbFile = vfsStream::newFile('db')
            ->at($folder)
            ->setContent($content);

        $loadedArray = $this->getInstance()->load($dbFile->url());

        $this->assertEquals(['exists' => true], $loadedArray);
    }

    public function testLoadFromCallback()
    {
        $content = "
        <?php

        return function () {
            return [
                'exists' => true
            ];
        };
        ";

        $folder = vfsStream::setup();
        $dbFile = vfsStream::newFile('db')
            ->at($folder)
            ->setContent($content);

        $loadedArray = $this->getInstance()->load($dbFile->url());

        $this->assertEquals(['exists' => true], $loadedArray);
    }

    public function getInstance()
    {
        return new PhpDriver();
    }
}