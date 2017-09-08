<?php

namespace Unity\Component\Config\Contracts;

interface IDriver
{
    /**
     * Returns the configuration value
     *
     * @param $keys
     *
     * @return mixed
     */
    function get($keys);

    /**
     * Checks if a configuration exists
     *
     * @param $keys
     *
     * @return bool
     */
    function has($keys);

    /**
     * Returns the configuration as an array
     *
     * @param $src
     *
     * @return array
     */
    function parse($src);
}