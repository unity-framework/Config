<?php

namespace Unity\Component\Config\Drivers\File;

use Unity\Component\Config\Drivers\File\Exceptions\ConfigFileNotFoundException;

class ArrayDriver extends FileDriver
{
    function __construct()
    {
        $this->setExt('php');
    }

    /**
     * Gets the configuration
     *
     * @param mixed $config The required configuration
     * @param $sources
     * @return mixed
     */
    function get($config, $sources)
    {
        $this->denote($config, $arrayFile, $arrayKeys);

        $configArray = $this->getConfigArray($arrayFile, $sources);

        return $this->getConfig($configArray, $arrayKeys);
    }

    /**
     * Gets the configuration array containing
     * in the array file
     *
     * @param $arrayFile
     * @param $sources
     * @return mixed
     * @throws ConfigFileNotFoundException
     */
    function getConfigArray($arrayFile, $sources)
    {
        if(is_array($sources))
            foreach ($sources as $source) {
                $configArray = $this->requireArrayFile($arrayFile, $source);

                if($configArray)
                    return $configArray;
            }

        $configArray = $this->requireArrayFile($arrayFile, $sources);

        if($configArray)
            return $configArray;

        throw new ConfigFileNotFoundException("Cannot find configuration file \"{$this->getFileNameWithExt($arrayFile)}\" in any specified sources");
    }

    /**
     * Requires the array containing in the array file
     *
     * @param $arrayFile
     * @param $source
     * @return mixed
     * @throws ConfigFileNotFoundException
     */
    function requireArrayFile($arrayFile, $source)
    {
        $configFile = $this->getFullPath($arrayFile, $source);

        if($this->fileExists($configFile))
            return require $configFile;
    }
}