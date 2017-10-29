<?php

namespace Unity\Component\Config\Drivers;

use Unity\Contracts\Config\Drivers\IDriver;

/**
 * Class YamlDriver.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 * @link   https://github.com/e200/
 */
class YamlDriver implements IDriver
{
    /**
     * Loads and returns the configurations array.
     *
     * @param $ymlfile
     *
     * @return array
     */
    public function load($ymlfile) : array
    {
        return yaml_parse_file($ymlfile);
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
