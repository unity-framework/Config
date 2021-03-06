<?php

namespace Unity\Component\Config\Drivers;

use Unity\Component\Config\Contracts\Drivers\IDriver;

/**
 * Class YamlDriver.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 *
 * @link   https://github.com/e200/
 */
class YamlDriver implements IDriver
{
    /**
     * Loads and returns the configs array.
     *
     * @param $ymlfile
     *
     * @return array
     */
    public function load($ymlfile) : array
    {
        return spyc_load_file($ymlfile);
    }

    /**
     * Returns supported extensions.
     *
     * @return array
     */
    public function extensions() : array
    {
        return ['yml', 'yaml'];
    }
}
