<?php

namespace Unity\Component\Configuration\Drivers;

interface DriverInterface
{
    /**
     * Gets the configuration
     *
     * @param mixed $config The required configuration
     * @param mixed $source The configuration source
     * @return mixed
     */
    function get($config, $source);
}