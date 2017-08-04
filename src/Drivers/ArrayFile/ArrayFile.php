<?php

namespace Unity\Component\Config\Drivers\ArrayFile;

use Unity\Component\Config\Drivers\ArrayFile\Exceptions\BadConfigStringException;
use Unity\Component\Config\Drivers\ArrayFile\Exceptions\ConfigFileNotFoundException;
use Unity\Component\Config\Drivers\DriverInterface;

class ArrayFile implements DriverInterface
{
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
     * @internal param $source
     */
    function resolve($config, $sources)
    {
        $values = $this->splitValues($config);
        $filename = $this->getConfigFileName($values);
        $this->unsetConfigFileName($values);
        $configArray = $this->getConfigArray($filename, $sources);

        return $this->getTheConfig($configArray, $values);
    }

    /**
     * Splits each value from the `$config` string
     *
     * These values are separated by a dot "."
     *
     * The first "root" value represents the configuration file
     * name, and the rest represents the array access key(s)
     * to the configuration value
     *
     * The root and the access key(s) should be provided
     *
     * @param $config
     * @param $filename
     * @param $keys
     * @return array
     * @throws BadConfigStringException
     */
    function splitValues($config, &$filename, &$keys)
    {
        $exp = explode('.', $config);

        if(count($exp) < 1)
            throw new BadConfigStringException;

        $filename = $exp[0];

        foreach ($exp as $param)
            $keys[] = $param;
    }

    /**
     * Gets the configuration file name
     * from the given array
     *
     * @param $values
     * @return mixed
     */
    function getConfigFileName($values)
    {
        return $values['configFileName'];
    }

    /**
     * Removes the root value from the given array
     *
     * @param $values
     */
    function unsetConfigFileName(&$values)
    {
        unset($values['configFileName']);
    }

    /**
     * Gets the full path of the configuration
     * file name
     *
     * @param $configFileName
     * @param $source
     * @return string
     */
    function getFullPath($configFileName, $source)
    {
        return $source . '/' . $configFileName . '.php';
    }

    /**
     * Requires the configuration file containing
     * the array with the configurations
     *
     * @param $filename
     * @param $sources
     * @return mixed
     * @throws ConfigFileNotFoundException
     */
    function getConfigArray($filename, $sources)
    {
        if(is_array($sources))
            foreach ($sources as $source) {
                $configFile = $this->getFullPath($filename, $source);

                if($this->configFileExists($configFile))
                    return require $configFile;
            }

        $configFile = $this->getFullPath($filename, $sources);

        if($this->configFileExists($configFile))
            return require $configFile;

        throw new ConfigFileNotFoundException("Cannot find configuration file \"${$configFile}\"");
    }

    function configFileExists($configFile)
    {
        return file_exists($configFile);
    }

    /**
     * Gets the configuration value
     *
     * @param $configArray
     * @param $values
     * @return mixed
     */
    function getTheConfig($configArray, $values)
    {
        $config = null;

        /**
         * We walk through the $values checking
         * for the each
         */
        for($i = 0; $i < count($values); $i++)
        {
            if($i == 0)
                $config = $configArray[$values[$i]];
            else
                $config = $config[$values[$i]];
        }

        return $config;
    }
}