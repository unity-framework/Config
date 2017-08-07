<?php

namespace Unity\Component\Config\Drivers;

interface DriverInterface
{
    /**
     * Gets the configuration
     *
     * @param mixed $config The required configuration
     * @param $source
     * @return mixed
     */
    function get($config, $source);
}