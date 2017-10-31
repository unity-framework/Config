<?php

use e200\MakeAccessible\Make;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Unity\Component\Config\Drivers\XmlDriver;

class XmlDriverTest extends TestCase
{
    public function testXml2Array()
    {
        $content = '<?xml version="1.0" encoding="utf-8" ?>
                    <document>
                        <database>
                            <user>root</user>
                            <psw>toor</psw>
                            <db>example</db>
                            <host>localhost</host>
                        </database>
                    </document>
                ';

        $folder = vfsStream::setup();
        $dbFile = vfsStream::newFile('db')
            ->at($folder)
            ->setContent($content);

        $accessibleInstance = $this->getAccessibleInstance();

        $xml = simplexml_load_file($dbFile->url());

        $loadedData = $accessibleInstance->xml2Array($xml);

        $this->assertInternalType('array', $loadedData);
        $this->assertCount(1, $loadedData);
        $this->assertArrayHasKey('database', $loadedData);

        $this->assertCount(4, $loadedData['database']);

        $subLoadedData = $loadedData['database'];
        $this->assertInternalType('array', $subLoadedData);

        $this->assertEquals('root', $subLoadedData['user']);
        $this->assertEquals('toor', $subLoadedData['psw']);
        $this->assertEquals('example', $subLoadedData['db']);
        $this->assertEquals('localhost', $subLoadedData['host']);
    }

    public function testXml2ArrayWith2NodesWithSameName()
    {
        $content = '<?xml version="1.0" encoding="utf-8" ?>
                    <document>
                        <database>
                            <user>1</user>
                            <psw>2</psw>
                            <db>3</db>
                            <host>4</host>
                        </database>
                        <database>                
                            <user>root</user>
                            <psw>toor</psw>
                            <db>example</db>
                            <host>localhost</host>
                            <security>true</security>
                        </database>
                    </document>
                ';

        $folder = vfsStream::setup();
        $dbFile = vfsStream::newFile('db')
            ->at($folder)
            ->setContent($content);

        $accessibleInstance = $this->getAccessibleInstance();

        $xml = simplexml_load_file($dbFile->url());

        $loadedData = $accessibleInstance->xml2Array($xml);

        $this->assertInternalType('array', $loadedData);
        $this->assertCount(1, $loadedData);
        $this->assertArrayHasKey('database', $loadedData);

        $this->assertCount(5, $loadedData['database']);

        $subLoadedData = $loadedData['database'];
        $this->assertInternalType('array', $subLoadedData);

        $this->assertEquals('root', $subLoadedData['user']);
        $this->assertEquals('toor', $subLoadedData['psw']);
        $this->assertEquals('example', $subLoadedData['db']);
        $this->assertEquals('localhost', $subLoadedData['host']);
        $this->assertEquals('true', $subLoadedData['security']);
    }

    public function testExtensions()
    {
        $extensions = $this->getInstance()->extensions();

        $this->count(1, $extensions);
        $this->assertTrue(in_array('xml', $extensions));
    }

    public function testLoad()
    {
        $content = '<?xml version="1.0" encoding="utf-8" ?>
                    <document>
                        <exists>true</exists>
                    </document>
                ';

        $folder = vfsStream::setup();
        $dbFile = vfsStream::newFile('db')
            ->at($folder)
            ->setContent($content);

        $loadedData = $this->getInstance()->load($dbFile->url());

        $this->assertInternalType('array', $loadedData);
        $this->assertCount(1, $loadedData);
        $this->assertArrayHasKey('exists', $loadedData);

        $this->assertEquals('true', $loadedData['exists']);
    }

    public function getInstance()
    {
        return new XmlDriver();
    }

    public function getAccessibleInstance()
    {
        return Make::accessible($this->getInstance());
    }
}
