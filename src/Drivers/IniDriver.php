<?php

namespace Unity\Component\Config\Drivers;

use Unity\Component\Config\Contracts\Drivers\IDriver;

/**
 * Class IniDriver.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 *
 * @link   https://github.com/e200/
 */
class IniDriver implements IDriver
{
    /**
     * Loads and returns the configs array.
     *
     * @param $inifile
     *
     * @return array
     */
    public function load($inifile) : array
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
