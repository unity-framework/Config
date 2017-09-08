<?php

namespace Unity\Component\Config\Drivers\File;

use Unity\Support\File;
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
    function parse($inifile)
    {
        if(File::exists($inifile))
            return parse_ini_file($inifile);
    }
}
