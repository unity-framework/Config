<?php

namespace Unity\Component\Config\Drivers;

use Unity\Contracts\Config\Drivers\IDriver;

class IniDriver implements IDriver
{
    /**
     * Returns the configuration as an array.
     *
     * @param $inifile
     *
     * @return array
     */
    public function parse($inifile) : array
    {
        return parse_ini_file($inifile);
    }

    /**
     * Returns supported extensions.
     *
     * @return array
     */
    public function extensions() : array
    {
        return ['ini'];
    }
}
