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

    /**
     * Returns the configuration array
     * @param $root string The root entry
     * @param $source string The source containing configuration(s)
     * @return array
     */
    function getConfigArray($root, $source);
}
