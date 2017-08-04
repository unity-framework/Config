<?php

namespace Unity\Component\Config\Drivers\ArrayFile;

use Unity\Component\Config\Drivers\Driver;
use Unity\Component\Config\Drivers\ArrayFile\Exceptions\ConfigFileNotFoundException;

class ArrayFile extends Driver
{
    protected $ext = 'php';

    /**
     * Gets the configuration
     *
     * @param mixed $config The required configuration
     * @param mixed $source The configuration source
     * @return mixed
     */
    function get($config, $source)
    {
        return $this->resolve($config, $source);
    }

    /**
     * Process the requested configuration
     *
     * @param $config
     * @param $sources
     * @return mixed
     */
    function resolve($config, $sources)
    {
        $arrayFileName = '';
        $arrayKeys = [];

        $this->denote($config, $arrayFileName, $arrayKeys);

        $configArray = $this->getConfigArray($arrayFileName, $sources);
        return $this->getConfig($configArray, $arrayKeys);
    }

    /**
     * Sets the extension of config files
     *
     * @param $ext
     */
    function setExt($ext)
    {
        $this->ext = $ext;
    }

    /**
     * Gets the extension of config files
     *
     * @return string
     */
    function getExt()
    {
        return $this->ext;
    }

    /**
     * Returns the full path to access the
     * configuration file based in the $source
     *
     * @param $filename
     * @param $source
     * @return string
     */
    function fullPath($filename, $source)
    {
        return $source . '/' . $filename . $this->getExt();
    }

    /**
     * Gets the configuration array inside the array file
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

        throw new ConfigFileNotFoundException("Cannot find configuration file \"${$arrayFile}\"");
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
        $configFileName = $this->fullPath($arrayFile, $source);

        if($this->fileExists($configFileName))
            return require $configFileName;
    }

    /**
     * Check if $filename file exists
     *
     * @param $filename
     * @return bool
     */
    function fileExists($filename)
    {
        return file_exists($filename);
    }
}