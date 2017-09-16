<?php

namespace Unity\Component\Config\Drivers\File;

use Unity\Component\Config\Drivers\FileDriver;

class IniDriver extends FileDriver
{
    /**
     * Returns the configuration as an array
     *
     * @param $inifile
     *
     * @return array
     */
    function parse($inifile) : array
    {
        return parse_ini_file($inifile);
    }
}
