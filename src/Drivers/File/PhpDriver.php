<?php

namespace Unity\Component\Config\Drivers\File;

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
        if(file_exists($phpfile))
            return require $phpfile;
    }
}