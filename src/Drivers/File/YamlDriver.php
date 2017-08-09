<?php

namespace Unity\Component\Config\Drivers\File;

class YamlDriver extends FileDriver
{
    /**
     * YamlDriver constructor.
     */
    function __construct()
    {
        $this->setExt('yml');
    }

    /**
     * Resolves and returns the array
     * containing yml configurations
     *
     * @param $ymlFile string File
     * containing yml configurations
     *
     * @return array Array with configurations
     */
    function resolve($ymlFile)
    {
        if($this->fileExists($ymlFile))
        {
            return yaml_parse_file($ymlFile);
        }
    }
}