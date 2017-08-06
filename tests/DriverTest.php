<?php

use PHPUnit\Framework\TestCase;
use Unity\Component\Config\Drivers\Driver;
use Unity\Component\Config\Drivers\ArrayFile\Exceptions\InvalidConfigStringException;

class DriverTest extends TestCase
{
    /**
     * @covers Driver::validate()
     *
     * Tests if validate throws InvalidConfigStringException
     * with an empty notation
     */
    function testValidateWithEmptyNotation()
    {
        $this->expectException(InvalidConfigStringException::class);
        $this->expectExceptionMessage(
                "The config string must have a root entry with at least one key.
                \nExample: database.user.
                \nWhere \"database\" is the root entry and \"user\" is the key."
        );

        $driver = new DriverImplementation;

        $driver->validate('');
    }

    /**
     * @covers Driver::validate()
     *
     * Tests if validate throws InvalidConfigStringException
     * with only the root in the notation
     */
    function testValidateWithOnlyRootInNotation()
    {
        $this->expectException(InvalidConfigStringException::class);
        $this->expectExceptionMessage(
            "The config string must have a root entry with at least one key.
                \nExample: database.user.
                \nWhere \"database\" is the root entry and \"user\" is the key."
        );

        $driver = new DriverImplementation;

        $driver->validate('database');
    }

    /**
     * @covers Driver::validate()
     *
     * Tests if validate does nothing with valid notations
     *
     * The assertNull is only for the phpunit don't
     * show the warning: This test did not perform any assertions
     */
    function testValidateWithValidNotation()
    {
        $driver = new DriverImplementation;

        $this->assertNull($driver->validate('database.user'));
    }

    /**
     * @covers Driver::denote()
     *
     * Tests if denote returns the $filename
     * and the $keys[0] with a root.key notation
     */
    function testDenoteWithOneKey()
    {
        $driver = new DriverImplementation;

        $driver->denote('root.toor', $filename, $keys);

        $this->assertEquals('root', $filename);
        $this->assertCount(1, $keys);
        $this->assertEquals('toor', $keys[0]);
    }

    /**
     * @covers Driver::denote()
     *
     * Tests if denote returns the $filename
     * and the $keys with a root.key1.key2... notation
     */
    function testDenoteWithMoreThenOneKey()
    {
        $driver = new DriverImplementation;

        $driver->denote('root.toor.troo', $filename, $keys);

        $this->assertEquals('root', $filename);
        $this->assertCount(2, $keys);
        $this->assertEquals('toor', $keys[0]);
        $this->assertEquals('troo', $keys[1]);
    }

    /**
     * @covers Driver::getConfig()
     *
     * Tests if getConfig() returns the
     * config value that references the
     * given notation
     *
     * This test tests getConfig() with
     * one key only
     */
    function testGetConfigWithOneKey()
    {
        /**
         * @var $configArray array Array with config values
         */
        $configArray = [
            'user' => 'root'
        ];

        /**
         * @var $searchKeys array Array with the keys
         * to be searched in the `$configArray`
         *
         * This array contains only one key: `user`,
         * so, we're asking for the `user` config value.
         *
         * `user` config value is `root`
         */
        $searchKeys = [
            'user'
        ];

        $driver = new DriverImplementation;

        $this->assertEquals(
            'root',
            $driver->getConfig($configArray, $searchKeys)
        );
    }

    /**
     * @covers Driver::getConfig()
     *
     * Tests if getConfig() returns the
     * config value that references the
     * given notation
     *
     * This test tests getConfig() with
     * more then key
     */
    function testGetConfigWithMoreThenOneKey()
    {
        /**
         * @var $configArray array Array with config values
         */
        $configArray = [
            'users' => [
                'e200' => 'Eleandro Duzentos'
            ]
        ];

        /**
         * @var $searchKeys array Array with the keys
         * to be searched in the `$configArray`
         *
         * This array contains two keys: `users`, `e200`
         * so, we're asking for `users.e200` config value.
         *
         * `users.e200` config value is `Eleandro Duzentos`
         */
        $searchKeys = [
            'users',
            'e200'
        ];

        $driver = new DriverImplementation;

        $this->assertEquals(
            'Eleandro Duzentos',
            $driver->getConfig($configArray, $searchKeys)
        );
    }
}

class DriverImplementation extends Driver
{
    /**
     * Gets the configuration
     *
     * @param mixed $config The required configuration
     * @param $sources
     * @return mixed
     */
    function get($config, $sources)
    {
        // TODO: Implement get() method.
    }
}