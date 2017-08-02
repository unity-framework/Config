<?php

namespace Unity\Component\Configuration;

use Unity\Component\Configuration\Drivers\DriverInterface;

class Configuration implements ConfigurationInterface
{
    protected $driver;
    protected $source;

    function __construct(DriverInterface $driver, $source)
    {
        $this->driver = $driver;
        $this->source = $source;
    }

    function get($config)
    {
        return $this->driver->get($config, $this->source);
    }
}