<?php

use e200\MakeAccessible\Make;
use PHPUnit\Framework\TestCase;
use Unity\Component\Config\Config;
use Unity\Component\Config\Exceptions\RuntimeModificationException;

class ConfigTest extends TestCase
{
    public function testAllowModifications()
    {
        $instance = $this->getAccessibleInstance([]);
        $this->assertFalse($instance->allowModifications);

        $instance = $this->getAccessibleInstance([], true);
        $this->assertTrue($instance->allowModifications);
    }

    public function testRecCount()
    {
        $expected = [
            'database' => [
                'user' => [
                    'name' => true,
                    'psw'  => false,
                ],
            ],
        ];

        $instance = $this->getAccessibleInstance($expected);

        $this->assertEquals(4, $instance->recCount($expected));
    }

    public function testInnerSet()
    {
        $data = [
            'database' => [
                'user' => [
                    'name' => true,
                    'psw'  => false,
                ],
            ],
        ];

        $instance = $this->getAccessibleInstance($data);

        $instance->innerSet(['database', 'user', 'psw'], true);
        $this->assertTrue($instance->data['database']['user']['psw']);
        $instance->innerSet(['database', 'user'], true);
        $this->assertTrue($instance->data['database']['user']);
        $instance->innerSet(['database'], true);
        $this->assertTrue($instance->data['database']);
    }

    public function testInnerGet()
    {
        $data = [
            'database' => [
                'user' => [
                    'name' => true,
                    'psw'  => false,
                ],
            ],
        ];

        $instance = $this->getAccessibleInstance($data);

        $this->assertArrayHasKey('user', $instance->innerGet(['database']));
        $this->assertArrayHasKey('name', $instance->innerGet(['database', 'user']));
        $this->assertArrayHasKey('psw', $instance->innerGet(['database', 'user']));

        $this->assertTrue($instance->innerGet(['database', 'user', 'name']));
        $this->assertFalse($instance->innerGet(['database', 'user', 'psw']));
    }

    public function testInnerHas()
    {
        $data = [
            'database' => [
                'user' => [
                    'name' => true,
                    'psw'  => false,
                ],
            ],
        ];

        $instance = $this->getAccessibleInstance($data);

        $this->assertTrue($instance->innerHas(['database']));
        $this->assertTrue($instance->innerHas(['database', 'user']));
        $this->assertTrue($instance->innerHas(['database', 'user', 'name']));
        $this->assertTrue($instance->innerHas(['database', 'user', 'psw']));

        $this->assertFalse($instance->innerHas(['db']));
        $this->assertFalse($instance->innerHas(['database', 'db']));
        $this->assertFalse($instance->innerHas(['database', 'user', 'db']));
    }

    public function testInnerUnset()
    {
        $data = [
            'database' => [
                'user' => [
                    'name' => true,
                    'psw'  => false,
                ],
            ],
        ];

        $accessibleInstance = $this->getAccessibleInstance($data);

        $accessibleInstance->innerUnset(['database', 'user', 'psw']);
        $this->assertArrayNotHasKey('psw', $accessibleInstance->data['database']['user']);
    }

    public function testCount()
    {
        $expected = [
            'database' => [
                'user' => [
                    'name' => true,
                    'psw'  => false,
                ],
            ],
        ];

        $instance = $this->getInstance($expected);

        $this->assertCount(4, $instance);
    }

    /**
     * Testing if sets.
     */
    public function testSet()
    {
        $accessibleInstance = $this->getAccessibleInstance([], true);
        $instance = $accessibleInstance->getInstance();

        $instance->set('can_cache', true);

        $this->assertArrayHasKey('can_cache', $accessibleInstance->data);
        $this->assertTrue($accessibleInstance->data['can_cache']);
    }

    /**
     * Testing if Replaces.
     *
     * @covers Config::set()
     */
    public function testIfSetReplaces()
    {
        $accessibleInstance = $this->getAccessibleInstance(['can_cache' => false], true);
        $instance = $accessibleInstance->getInstance();

        $instance->set('can_cache', true);

        $this->assertArrayHasKey('can_cache', $accessibleInstance->data);
        $this->assertTrue($accessibleInstance->data['can_cache']);

        $instance->set('database.user.exists', true);

        $this->assertArrayHasKey('database', $accessibleInstance->data);
        $this->assertArrayHasKey('user', $accessibleInstance->data['database']);
        $this->assertArrayHasKey('exists', $accessibleInstance->data['database']['user']);
        $this->assertTrue($accessibleInstance->data['database']['user']['exists']);
    }

    /**
     * @covers Config::set()
     */
    public function testRuntimeModificationExceptionOnSet()
    {
        $this->expectException(RuntimeModificationException::class);

        $instance = $this->getInstance([], false);

        $instance->set('', null);
    }

    /**
     * @covers Config::set()
     */
    public function testRuntimeModificationExceptionOnSetByDefault()
    {
        $this->expectException(RuntimeModificationException::class);

        $instance = $this->getInstance([]);

        $instance->set('', null);
    }

    public function testGet()
    {
        $instance = $this->getInstance([
            'config' => [
                    'is_working' => true,
                ],
            ]);

        $this->assertTrue($instance->get('config.is_working'));
    }

    public function testHas()
    {
        $instance = $this->getInstance([
            'config' => [
                    'is_working' => null,
                ],
            ]);

        $this->assertTrue($instance->has('config.is_working'));
        $this->assertFalse($instance->has('config.not_working'));

        $this->assertTrue($instance->has('config'));
        $this->assertFalse($instance->has('configurations'));
    }

    public function testOffsetSet()
    {
        $accessibleInstance = $this->getAccessibleInstance([], true);
        $instance = $accessibleInstance->getInstance();

        $instance['is_working'] = true;
        $this->assertTrue($accessibleInstance->data['is_working']);
    }

    /**
     * Testing if replaces.
     *
     * @covers Config::offsetSet()
     */
    public function testOffsetSetReplace()
    {
        $accessibleInstance = $this->getAccessibleInstance(['is_working' => false], true);
        $instance = $accessibleInstance->getInstance();

        $instance['is_working'] = true;
        $this->assertTrue($accessibleInstance->data['is_working']);
    }

    public function testOffsetGet()
    {
        $data = [
            'database' => [
                'can_cache' => true,
            ],
        ];

        $instance = $this->getInstance($data);

        $this->assertTrue($instance['database']['can_cache']);
    }

    /**
     * Tests if `Config::offsetGet()` returns
     * the configuration by reference and if we can
     * modify it when `Config::isOnReadOnlyMode()`
     * is disabled.
     *
     * @covers Config::offsetGet()
     */
    public function testSetUsingOffsetGetReturnedReference()
    {
        $data = [
            'database' => [
                'can_cache' => false,
            ],
        ];

        $accessibleInstance = $this->getAccessibleInstance($data, true);
        $instance = $accessibleInstance->getInstance();

        $instance['database']['can_cache'] = true;

        $this->assertSame(
            $accessibleInstance->data['database']['can_cache'],
            $instance['database']['can_cache']
        );
        $this->assertTrue($instance['database']['can_cache']);
    }

    /**
     * Tests if `Config::offsetGet()` don't returns
     * the configuration by reference and if we can't
     * modify it when `Config::isOnReadOnlyMode()`
     * is enabled.
     *
     * @covers Config::offsetGet()
     */
    public function testSetUsingOffsetGetNotReturnedReference()
    {
        $data = [
            'database' => [
                'can_cache' => false,
            ],
        ];

        $instance = $this->getInstance($data, false);

        $instance['database']['can_cache'] = true;

        $this->assertFalse($instance['database']['can_cache']);
    }

    public function testOffsetExists()
    {
        $data = [
            'database' => [
                'can_cache' => null,
            ],
        ];

        $instance = $this->getInstance($data);

        $this->assertArrayHasKey('database', $instance);
        $this->assertArrayHasKey('can_cache', $instance['database']);

        $this->assertTrue(isset($instance['database']));

        /*
         * TODO: Fix this shit: $this->assertTrue(array_key_exists('database', $instance));
         */

        $this->assertFalse(isset($instance['db']));
        $this->assertFalse(array_key_exists('db', (array) $instance));
    }

    public function testOffsetUnset()
    {
        $data = [
            'database' => [
                'can_cache' => null,
            ],
        ];

        $accessibleInstance = $this->getAccessibleInstance($data, true);
        $instance = $accessibleInstance->getInstance();

        unset($instance['database']['can_cache']);
        $this->assertArrayNotHasKey('can_cache', $accessibleInstance->data['database']);

        unset($instance['database']);
        $this->assertArrayNotHasKey('can_cache', $instance);
    }

    public function testRuntimeModificationExceptionOnOffsetUnset()
    {
        $this->expectException(RuntimeModificationException::class);

        $instance = $this->getInstance();

        unset($instance['database']);
    }

    public function testGetAll()
    {
        $expected = ['data'];

        $instance = $this->getInstance($expected);

        $this->assertEquals($expected, $instance->getAll());
    }

    public function getInstance($data = [], $allowModifications = false)
    {
        return new Config($data, $allowModifications);
    }

    public function getAccessibleInstance($data = [], $allowModifications = false)
    {
        return Make::accessible($this->getInstance($data, $allowModifications));
    }
}
