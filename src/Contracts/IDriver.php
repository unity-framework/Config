<?php

namespace Unity\Component\Config\Contracts;

interface IDriver
{
    /**
     * Loads all configurations from the source.
     *
     * @param $source mixed Configurations source
     *
     * @return array
     */
    public function load($source) : array;
}
