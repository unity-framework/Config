<?php

use PHPUnit\Framework\TestCase;
use Unity\Component\Config\Drivers\ArrayFile\ArrayFile;
use Unity\Component\Config\Drivers\ArrayFile\Exceptions\ConfigFileNotFoundException;

class ArrayFileTest extends TestCase
{
    /**
     * @covers ArrayFile::setExt()
     * @covers ArrayFile::getExt()
     *
     * Should set and get the config file extension
     */
    function testSetGetExt()
    {
        $driver = $this->getArrayFileDriverForTest();

        /** php is the default extension */
        $this->assertEquals('php', $driver->getExt());

        /** Changed extension to "inc" */
        $driver->setExt('inc');

        /** Should return the new extension */
        $this->assertEquals('inc', $driver->getExt());
    }

    /**
     * @covers ArrayFile::getFileNameWithExt()
     *
     * Should return $filename concatenation
     * with the extension
     */
    function testGetFileNameWithExtension()
    {
        $driver = $this->getArrayFileDriverForTest();

        /** Should return the filename with the default extension */
        $filename = $driver->getFileNameWithExt('config');
        $this->assertEquals('config.php', $filename);
    }

    /**
     * @covers ArrayFile::getFullPath()
     *
     * Should return the $filename concatenation
     * with $source
     */
    function testGetFullPath()
    {
        $driver = $this->getArrayFileDriverForTest();

        /**
         * Should return the $source concatenated
         * with $filename and the default extension
         */
        $fullPath = $driver->getFullPath('config', '/');
        $this->assertEquals('/' . 'config.php', $fullPath);
    }

    /**
     * @covers ArrayFile::fileExists()
     *
     * Should return if a file exists
     */
    function testFileExists()
    {
        $driver = $this->getArrayFileDriverForTest();
        $source = $this->getSourceForTest();

        /**
         * Should return false for non existent files
         */
        $exists = $driver->fileExists('');
        $this->assertEquals(false, $exists);

        /**
         * Should return true for existent files
         */
        $exists = $driver->fileExists($source . '/' . 'database.php');
        $this->assertEquals(true, $exists);
    }

    /**
     * @covers ArrayFile::requireArrayFile()
     *
     * Should require and return the array
     * with configuration values
     */
    function testRequireArrayFile()
    {
        $driver = $this->getArrayFileDriverForTest();
        $source = $this->getSourceForTest();

        $configArray = $driver->requireArrayFile('database', $source);

        $this->assertInternalType('array', $configArray);
        $this->assertCount(6, $configArray);
    }

    /**
     * @covers ArrayFile::getConfigArray()
     *
     * Should return the configuration array
     */
    function testGetConfigArray()
    {
        $driver = $this->getArrayFileDriverForTest();
        $source = $this->getSourceForTest();

        $configArray = $driver->getConfigArray('database', $source);

        $this->assertInternalType('array', $configArray);
        $this->assertCount(6, $configArray);
    }

    /**
     * @covers ArrayFile::getConfigArray()
     *
     * Should return the configuration array
     */
    function testGetConfigArrayWithSourcesArray()
    {
        $driver = $this->getArrayFileDriverForTest();
        $sources = $this->getSourcesForTest();

        $configArray = $driver->getConfigArray('database', $sources);

        $this->assertInternalType('array', $configArray);
        $this->assertCount(6, $configArray);
    }

    /**
     * @covers ArrayFile::get()
     *
     * Tests if get() throws ConfigFileNotFoundException
     * with non existing configurations
     *
     * In this case get() is searching for
     * NotExistingPath/foo.php
     */
    function testGetNonExistingConfig()
    {
        $this->expectException(ConfigFileNotFoundException::class);

        $driver = $this->getArrayFileDriverForTest();

        $driver->get('foo.bar', 'NotExistingPath/');
    }

    /**
    * @covers ArrayFile::resolve()
    *
    * Should return the value of database.user = 'root'
    */
    function testResolve()
    {
        $drive = $this->getArrayFileDriverForTest();
        $source = $this->getSourceForTest();

        $this->assertEquals('root', $drive->resolve('database.user', $source));
    }

    /**
     * @covers ArrayFile::get()
     *
     * Should return the value of database.user = 'root'
     */
    function testGet()
    {
        $drive = $this->getArrayFileDriverForTest();
        $source = $this->getSourceForTest();

        $this->assertEquals('root', $drive->get('database.user', $source));
    }

    /**
     * Sources must end with a slash "/"
     *
     * @return string
     */
    function getSourceForTest()
    {
        return __DIR__ . '/configs/';
    }

    /**
     * Sources must end with a slash "/"
     *
     * @return string
     */
    function getSourcesForTest()
    {
        return [
            __DIR__ . '/configs/',
            __DIR__ . '/files/',
            __DIR__ . '/archives/'
        ];
    }

    function getArrayFileDriverForTest()
    {
        return new ArrayFile;
    }
}