<?php

use PHPUnit\Framework\TestCase;
use Unity\Component\Config\Drivers\Driver;
use Unity\Component\Config\Exceptions\ConfigNotFoundException;
use Unity\Component\Config\Exceptions\InvalidConfigStringException;

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

        $driver = new DriverImplementor;

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

        $driver = new DriverImplementor;

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
        $driver = new DriverImplementor;

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
        $driver = new DriverImplementor;

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
        $driver = new DriverImplementor;

        $driver->denote('root.toor.troo', $filename, $keys);

        $this->assertEquals('root', $filename);
        $this->assertCount(2, $keys);
        $this->assertEquals('toor', $keys[0]);
        $this->assertEquals('troo', $keys[1]);
    }

    /**
     * @covers Driver::search()
     *
     * First assertion should return 'root'
     * Second assertion should return `null`
     */
    function testSearch()
    {
        $driver = new DriverImplementor;

        $config = $driver->search('user', ['user' => 'root']);
        $this->assertEquals('root', $config);

        $config = $driver->search(null, null);
        $this->assertNull($config);
    }

    /**
     * @covers Driver::getConfigValue()
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

        $driver = new DriverImplementor;

        $this->assertEquals(
            'root',
            $driver->getConfigValue($configArray, $searchKeys)
        );
    }

    /**
     * @covers Driver::getConfigValue()
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

        $driver = new DriverImplementor;

        $this->assertEquals(
            'Eleandro Duzentos',
            $driver->getConfigValue($configArray, $searchKeys)
        );
    }

    /**
     * @covers Driver::get()
     *
     * `get()` should return 'root'
     */
    function testGet()
    {
        $driver = new DriverImplementor;
        $source = $this->getSourceForTest();

        $config = $driver->get('database.user', $source);
        $this->assertEquals('root', $config);
    }

    /**
     * @covers Driver::get()
     *
     * Tests if get() throws ConfigFileNotFoundException
     * with non existing configurations
     */
    function testGetNonExistingConfig()
    {
        $this->expectException(ConfigNotFoundException::class);

        $driver =  new DriverImplementor;

        $driver->get('foo.bar', null);
    }

    /**
     * @return string
     */
    private function getSourceForTest()
    {
        return __DIR__ . '/array';
    }
}

class DriverImplementor extends Driver
{
    /**
     * Returns the configuration array for test purposes
     */
    function getConfigArray($root, $source)
    {
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
