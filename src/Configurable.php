<?php

namespace Unity\Component\Configurable;

use Unity\Component\Configurable\Drivers\DriverInterface;

class Configurable implements ConfigurableInterface
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