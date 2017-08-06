<?php

namespace Unity\Component\Config\Drivers\File;

use Unity\Component\Config\Drivers\File\Exceptions\ConfigFileNotFoundException;

class JsonDriver extends FileDriver
{
    /**
     * JsonDriver constructor.
     */
    function __construct()
    {
        parent::__construct('json');
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
        $this->denote($config, $jsonFile, $keys);
        
        $configArray = $this->getConfigArray($jsonFile, $sources);

        return $this->getConfig($configArray, $keys);
    }

    function getConfigArray($jsonFile, $sources)
    {
        if(is_array($sources))
            foreach ($sources as $source) {
                $configArray = $this->getJsonAsArray($jsonFile, $source);

                if($configArray)
                    return $configArray;
            }

        $configArray = $this->getJsonAsArray($jsonFile, $sources);

        if($configArray)
            return $configArray;

        throw new ConfigFileNotFoundException("Cannot find configuration file \"{$this->getFileNameWithExt($arrayFile)}\" in any specified sources");
    }

    function getJsonAsArray($jsonFile, $source)
    {
        $fileFullPath = $this->getFullPath($jsonFile, $source);

        if($this->fileExists($fileFullPath))
        {
            $fileContent = file_get_contents($fileFullPath);

            return (array)json_decode($fileContent);
        }
    }
}