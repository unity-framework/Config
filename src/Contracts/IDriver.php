<?php

namespace Unity\Component\Config\Contracts;

interface IDriver
{
    /**
     * Returns the configuration value.
     *
     * @param $keys
     *
     * @return mixed
     */
    public function get($keys);

    /**
     * Checks if a configuration exists.
     *
     * @param $keys
     *
     * @return bool
     */
    public function has($keys);

    /**
     * Returns the configuration as an array.
     *
     * @param $src
     *
     * @return array
     */
    public function parse($src);
}
