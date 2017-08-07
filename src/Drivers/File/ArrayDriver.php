<?php

namespace Unity\Component\Config\Drivers\File;

class ArrayDriver extends FileDriver
{
    function __construct()
    {
        $this->setExt('php');
    }

    /**
     * Resolves and returns the array
     * containing configurations
     *
     * @param $arrayFile string File
     * containing an array with configurations
     *
     * @return array Array with configurations
     */
    function resolve($arrayFile)
    {
        if ($this->fileExists($arrayFile))
            return require $arrayFile;
    }
}