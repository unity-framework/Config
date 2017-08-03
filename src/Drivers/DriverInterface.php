<?php

namespace Unity\Component\Configuration\Drivers;

interface DriverInterface
{
    /**
     * Gets the configuration
     *
     * @param mixed $config The required configuration
     * @param $sources
     * @return mixed
     */
    function get($config, $sources);
}