<?php

namespace Unity\Component\Config\Drivers\File;

use Unity\Component\Config\Drivers\FileDriver;

class PhpDriver extends FileDriver
{
    /**
     * Returns the configuration as an array.
     *
     * @param $phpfile
     *
     * @return array
     */
    public function parse($phpfile) : array
    {
        return require $phpfile;
    }
}
