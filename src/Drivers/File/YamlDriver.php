<?php

/*
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 */

namespace Unity\Component\Config\Drivers\File;

use Unity\Component\Config\Drivers\FileDriver;
use Unity\Support\File;

class YamlDriver extends FileDriver
{
    /**
     * Returns the configuration as an array.
     *
     * @param $ymlfile
     *
     * @return array
     */
    public function parse($ymlfile)
    {
        if (File::exists($ymlfile)) {
            return yaml_parse_file($ymlfile);
        }
    }
}
