<?php

use PHPUnit\Framework\TestCase;
use Unity\Component\Config\ConfigBuilder;
use Unity\Component\Config\ConfigInterface;
use Unity\Component\Config\Drivers\File\YamlDriver;
use Unity\Component\Config\Exceptions\RequiredSourceException;

class ConfigBuilderTest extends TestCase
{
    /**
     * @covers ConfigBuilder::setSource()
     * @covers ConfigBuilder::getSource()
     *
     * `setSource()` should set the given
     * source and return the owner instance
     */
    function testSetGetSource()
    {
        $builder = $this->getConfigBuilderForTest();

        $builderInstance = $builder->setSource('someSource');

        $this->assertEquals('someSource', $builder->getSource());
        $this->assertSame($builder, $builderInstance);
    }

    /**
     * @covers ConfigBuilder::addSource()
     *
     * `addSource()` should add all the given items
     */
    function testAddSource()
    {
        $builder = $this->getConfigBuilderForTest();

        for($i = 0; $i < 3; $i++)
            $builder->addSource('source' . $i);

        $source = $builder->getSource();

        $this->assertCount(3, $source);

        for($i = 0; $i < 2; $i++)
            $this->assertEquals('source' . $i, $source[$i]);
    }

    /**
     * @covers ConfigBuilder::setDriver()
     * @covers ConfigBuilder::makeDriver()
     *
     * `setDriver()` should set the driver and
     * return the owner instance
     *
     * `makeDriver()` should return an instance
     * of driver instance of the class setted in
     * the `setDriver()`
     */
    function testSetGetDriver()
    {
        $builder = $this->getConfigBuilderForTest();

        $builderInstance = $builder->setDriver(YamlDriver::class);

        $driver = $builder->makeDriver();

        $this->assertSame($builder, $builderInstance);
        $this->assertInstanceOf(YamlDriver::class, $driver);
    }

    /**
     * @covers ConfigBuilder::build()
     */
    function testBuild()
    {
        $config = (new ConfigBuilder)
            ->setSource('source')
            ->build();

        $this->assertInstanceOf(ConfigInterface::class, $config);
    }

    /**
     * @covers RequiredSourceException
     */
    function testBuildRequiredSourceException()
    {
        $this->expectException(RequiredSourceException::class);

        (new ConfigBuilder)
            ->build();
    }

    /**
     * @return ConfigBuilder
     */
    private function getConfigBuilderForTest()
    {
        return new ConfigBuilder;
    }
}