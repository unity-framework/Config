<?php

namespace Unity\Component\Config\Drivers\File;

use Unity\Support\File;
use Unity\Component\Config\Drivers\FileDriver;

class PhpDriver extends FileDriver
{
    /**
     * Returns the configuration as an array
     *
     * @param $phpfile
     *
     * @return array
     */
    function parse($phpfile)
    {
        if(File::exists($phpfile))
            return require $phpfile;
    }
}
