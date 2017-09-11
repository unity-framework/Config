<?php

namespace Unity\Component\Config\Drivers\File;

use Unity\Support\File;
use Unity\Component\Config\Drivers\FileDriver;

class JsonDriver extends FileDriver
{
    /**
     * Returns the configuration as an array
     *
     * @param $jsonfile
     *
     * @return array
     */
    function parse($jsonfile) : array
    {
        $file_content = file_get_contents($jsonfile);

        return (array)json_decode($file_content);
    }
}
