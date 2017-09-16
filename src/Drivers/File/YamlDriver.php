<?php

namespace Unity\Component\Config\Drivers\File;

use Unity\Component\Config\Drivers\FileDriver;

class YamlDriver extends FileDriver
{
    /**
     * Returns the configuration as an array.
     *
     * @param $ymlfile
     *
     * @return array
     */
    public function parse($ymlfile) : array
    {
        return yaml_parse_file($ymlfile);
    }
}
