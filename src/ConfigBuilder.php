<?php

namespace Unity\Component\Config;

use Unity\Component\Config\Drivers\DriverInterface;
use Unity\Component\Config\Drivers\File\ArrayDriver;
use Unity\Component\Config\Exceptions\RequiredSourceException;

class ConfigBuilder
{
    /**
     * By default, ArrayDriver is the default driver
     *
     * @var string
     */
    protected $driver = ArrayDriver::class;

    /** @var $source string Configurations source(s) */
    protected $source;

    /**
     * Sets where to search for configurations
     *
     * The `$source` value depends on the driver
     * you're using, for drivers that extends from
     * the `FileDriver` abstract class, the `$source`
     * is the path where your configuration files are
     *
     * @param $source string|array
     * @return $this
     */
    function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Adds a source to a collection of sources.
     *
     * Use this function if you have more then
     * one source for your configurations.
     *
     * @param $source
     */
    function addSource($source)
    {
        $this->source[] = $source;
    }

    /**
     * Gets the source of configurations
     *
     * @return mixed
     */
    function getSource()
    {
        return $this->source;
    }

    /**
     * Selects the driver to be used
     *
     * @param $driver string|object
     * @return $this
     */
    function setDriver($driver)
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * Instantiates the selected driver
     *
     * @return string|object
     */
    function makeDriver()
    {
        /**
         * If the user passed a DriverInterface object
         * we just return it, otherwise we instantiate
         * the driver selected by the user.
         */
        if(class_implements(DriverInterface::class))
            return $this->driver;

        return new $this->driver;
    }

    /**
     * Builds the configuration instance
     *
     * @return Config
     * @throws RequiredSourceException
     */
    function build()
    {
        if(is_null($this->getSource()))
            throw new RequiredSourceException("You must specify a source before you build a Config class instance.");

        return new Config($this->makeDriver(), $this->getSource());
    }
}