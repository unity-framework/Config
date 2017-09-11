<?php

/*
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 */

namespace Unity\Component\Config\Drivers\File;

use Unity\Component\Config\Drivers\FileDriver;
use Unity\Support\File;

class PhpDriver extends FileDriver
{
    /**
     * Returns the configuration as an array.
     *
     * @param $phpfile
     *
     * @return array
     */
    public function parse($phpfile)
    {
        if (File::exists($phpfile)) {
            return require $phpfile;
        }
    }
}
