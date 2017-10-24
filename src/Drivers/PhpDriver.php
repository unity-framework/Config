<?php

namespace Unity\Component\Config\Drivers;

use Unity\Contracts\Config\Drivers\IDriver;

class PhpDriver implements IDriver
{
    /**
     * Returns the configuration as an array
     *
     * @param $phpfile
     *
     * @return array
     */
    function parse($phpfile) : array
    {
        return require $phpfile;
    }

     /**
     * Returns supported extensions.
     * 
     * @return array
     */
    function extensions() : array
    {
        return ['php', 'inc'];
    }
}
