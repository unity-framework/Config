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
        $this->denote($config, $arrayFile, $arrayKeys);

        $configArray = $this->getConfigArray($arrayFile, $sources);

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

        throw new ConfigFileNotFoundException("Cannot find configuration file \"{$this->getFileNameWithExt($arrayFile)}\" in any specified sources");
    }

    /**
     * Gets the filename with extension
     *
     * @param $filename
     * @return string
     */
    function getFileNameWithExt($filename)
    {
        return $filename . '.' . $this->getExt();
    }

    /**
     * Returns the full path to access the
     * configuration file based in the $source
     *
     * @param $filename
     * @param $source
     * @return string
     */
    function getFullPath($filename, $source)
    {
        return $source . $this->getFileNameWithExt($filename);
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

    /**
     * Checks if $filename file exists
     *
     * @param $configFile
     * @return bool
     */
    function fileExists($configFile)
    {
        return file_exists($configFile);
    }
}