<?php

namespace Unity\Component\Config\Drivers\File;

class JsonDriver extends FileDriver
{
    /**
     * JsonDriver constructor.
     */
    function __construct()
    {
        $this->setExt('json');
    }

    /**
     * Resolves and returns the array
     * containing jSON configurations
     *
     * @param $jsonFile string File
     * containing jSON configurations
     *
     * @return array Array with configurations
     */
    function resolve($jsonFile)
    {
        if($this->fileExists($jsonFile))
        {
            $fileContent = file_get_contents($jsonFile);

            return (array)json_decode($fileContent);
        }
    }
}