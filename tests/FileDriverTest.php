<?php

use PHPUnit\Framework\TestCase;
use Unity\Component\Config\Drivers\File\FileDriver;

class FileDriverTest extends TestCase
{
    /**
     * @covers FileDriver::setExt()
     * @covers FileDriver::getExt()
     *
     * Should set and get the config file extension
     */
    function testSetGetHasExt()
    {
        /**
         * Here we use a new Instance 'cause
         * we want an instance without an extension
         * set, so we can test if FileDriver::has()
         * is working
         */
        $driver = new FileDriverImplementation;

        /** Should return the parent::__constructor() extension */
        $this->assertEquals('inc', $driver->getExt());

        /**
         * Should return `false` since we don't provided
         * any ext yet
         */
        $this->assertTrue($driver->hasExt());

        /** Change extension to "json" */
        $driver->setExt('json');

        /** Should return the new extension */
        $this->assertEquals('json', $driver->getExt());

        /**
         * Should return `true` since we provided an extension
         */
        $this->assertTrue($driver->hasExt());

        /** Change extension to ".php" */
        $driver->setExt('.php');

        /** Should return the `php` without the dot */
        $this->assertEquals('php', $driver->getExt());
    }

    /**
     * @covers FileDriver::setExt()
     *
     * `setExt()` should return the extension without the dot
     */
    function testSetExtWithDot()
    {
        $driver = $this->getFileDriverImplementationForTest();

        $driver->setExt('.php');

        $this->assertEquals('php', $driver->getExt());
    }

    /**
     * @covers FileDriver::getFilenameWithExt()
     *
     * Should return $filename concatenation
     * with the extension
     */
    function testGetFilenameWithExtension()
    {
        $driver = $this->getFileDriverImplementationForTest();

        /** Should return the filename with extension */
        $filename = $driver->getFilenameWithExt('config');
        $this->assertEquals('config.php', $filename);
    }

    /**
     * @covers FileDriver::getFullPath()
     *
     * Should return the $filename concatenation
     * with $source
     */
    function testGetFullPath()
    {
        $driver = $this->getFileDriverImplementationForTest();

        /**
         * Should return the $source concatenated
         * with $filename and the default extension
         */
        $fullPath = $driver->getFullPath('config', '/');
        $this->assertEquals('/' . 'config.php', $fullPath);
    }

    /**
     * @covers FileDriver::fileExists()
     *
     * Should return if a file exists
     */
    function testFileExists()
    {
        $driver = $this->getFileDriverImplementationForTest();
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
     * Sources must end with a slash "/"
     *
     * @return string
     */
    private function getSourceForTest()
    {
        return __DIR__ . '/arrays/';
    }

    private function getFileDriverImplementationForTest()
    {
        static $driver;

        if(is_null($driver))
        {
            $driver = new FileDriverImplementation;

            $driver->setExt('php');
        }

        return $driver;
    }
}

class FileDriverImplementation extends FileDriver
{
    function __construct()
    {
        parent::__construct('inc');
    }

    function get($config, $source)
    {

    }
}