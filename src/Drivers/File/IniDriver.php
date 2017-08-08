<?php

namespace Unity\Component\Config\Drivers\File;

class IniDriver extends FileDriver
{
    /**
     * IniDriver constructor.
     */
    function __construct()
    {
        $this->setExt('ini');
    }

    /**
     * Resolves and returns the array
     * containing ini configurations
     *
     * @param $iniFile string File
     * containing ini configurations
     *
     * @return array Array with configurations
     */
    function resolve($iniFile)
    {
        if($this->fileExists($iniFile))
        {
            return parse_ini_file($iniFile);
        }
    }
}