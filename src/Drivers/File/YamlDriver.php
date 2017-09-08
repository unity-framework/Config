<?php

namespace Unity\Component\Config\Drivers\File;

use Unity\Support\File;
use Unity\Component\Config\Drivers\FileDriver;

class YamlDriver extends FileDriver
{
    /**
     * Returns the configuration as an array
     *
     * @param $ymlfile
     *
     * @return array
     */
    function parse($ymlfile)
    {
        if(File::exists($ymlfile))
            return yaml_parse_file($ymlfile);
    }
}
