<?php

namespace Unity\Component\Config;

use Unity\Component\Config\Drivers\DriverInterface;

class Config implements ConfigInterface
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