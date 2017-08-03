<?php

namespace Unity\Component\Configuration\Drivers\ArrayFile;

use Unity\Component\Configuration\Drivers\ArrayFile\Exceptions\BadConfigStringException;
use Unity\Component\Configuration\Drivers\ArrayFile\Exceptions\ConfigFileNotFoundException;
use Unity\Component\Configuration\Drivers\DriverInterface;

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
     * @param $source
     * @return mixed
     */
    function resolve($config, $source)
    {
        $values = $this->splitValues($config);
        $filename = $this->getConfigFileName($values);
        $this->unsetConfigFileName($values);
        $configFile = $this->getFullPath($filename, $source);
        $configArray = $this->getConfigArray($configFile);

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
     * @return array
     * @throws BadConfigStringException
     */
    function splitValues($config)
    {
        $exp = explode('.', $config);

        if(count($exp) < 1)
            throw new BadConfigStringException;

        $params['configFileName'] = $exp[0];

        unset($exp[0]);

        foreach ($exp as $param)
            $params[] = $param;

        return $params;
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
     * @param $configFile
     * @return mixed
     * @throws ConfigFileNotFoundException
     */
    function getConfigArray($configFile)
    {
        if(file_exists($configFile))
            return require $configFile;

        throw new ConfigFileNotFoundException("Cannot find configuration file \"${$configFile}\"");
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