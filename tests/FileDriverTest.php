<?php

use PHPUnit\Framework\TestCase;
use Unity\Component\Config\Drivers\File\Exceptions\ConfigNotFoundException;
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
        $driver = new Implementor;

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
        $driver = $this->getImplementorForTest();

        $driver->setExt('.php');

        $this->assertEquals('php', $driver->getExt());
    }

    /**
     * @covers FileDriver::getFilenameWithExt()
     *
     * `getFilenameWithExt()` should return $filename
     * concatenation with the extension
     */
    function testGetFilenameWithExtension()
    {
        $driver = $this->getImplementorForTest();

        $filename = $driver->getFilenameWithExt('config');
        $this->assertEquals('config.php', $filename);
    }

    /**
     * @covers FileDriver::getFile()
     *
     * `getFile()` should return the `$filename` concatenation
     * with `$source`
     */
    function testGetFile()
    {
        $driver = $this->getImplementorForTest();

        $file = $driver->getFile('config', '/');
        $this->assertEquals('/' . 'config.php', $file);
    }

    /**
     * @covers FileDriver::fileExists()
     *
     * `fileExists()` should return true if a file exists
     */
    function testFileExists()
    {
        $driver = $this->getImplementorForTest();
        $source = $this->getSourceForTest();

        /**
         * Should return false 'cause file `null`
         * does'nt exists
         */
        $exists = $driver->fileExists(null);
        $this->assertEquals(false, $exists);

        /**
         * Should return true 'cause `database` file exists
         */
        $exists = $driver->fileExists($source . '/' . 'database.php');
        $this->assertEquals(true, $exists);
    }

    /**
     * @covers FileDriver::callResolver()
     *
     * `callResolver()` should return the
     * `Implementor::resolve() return value
     */
    function testCallResolver()
    {
        $driver = $this->getImplementorForTest();
        $source = $this->getSourceForTest();

        /**
         * Note: We don't provide a `$filename`,
         * since our resolver don't use the `$filename`
         * to returns the configuration array
         */
        $configArray = $driver->callResolver(null, $source);

        $this->assertInternalType('array', $configArray);
        $this->assertCount(6, $configArray);
    }

    /**
     * @covers FileDriver::getResolverMethod()
     *
     * `getResolverMethod()` should return the
     * resolver method name implemented by the
     * `Implementor`
     */
    function testGetResolverMethod()
    {
        $driver = $this->getImplementorForTest();

        $this->assertEquals('resolve', $driver->getResolverMethod());
    }

    /**
     * @covers FileDriver::getConfigArray()
     *
     * Should return the configuration array
     */
    function testGetConfigArray()
    {
        $driver = $this->getImplementorForTest();
        $source = $this->getSourceForTest();

        $configArray = $driver->getConfigArray('database', $source);

        $this->assertInternalType('array', $configArray);
        $this->assertCount(6, $configArray);
    }

    /**
     * @return string
     */
    private function getSourceForTest()
    {
        return __DIR__ . '/array';
    }

    /**
     * @return Implementor
     */
    private function getImplementorForTest()
    {
        static $driver;

        if(is_null($driver))
        {
            $driver = new Implementor;

            $driver->setExt('php');
        }

        return $driver;
    }
}

class Implementor extends FileDriver
{
    function __construct()
    {
        $this->setExt('inc');
    }

    function resolve($file)
    {
        /**
         * We must return an array containing data
         * or `Implementor::getConfigArray()` will
         * throw a ConfigFileNotFoundException
         */
        return [
            'user' => 'root',
            'psw' => '1234',
            'db' => 'example',
            'host' => '127.0.0.1',

            'cache_queries' => true,
            'timeout' => 1000
        ];
    }
}
