<?php

namespace Unity\Component\Configuration\Drivers;

class ArrayFile implements DriverInterface
{
    function get($config, $source)
    {
        return $this->resolve($config, $source);
    }

    function resolve($config, $source)
    {
        $values = $this->splitValues($config);
        $filename = $this->getFilename($values);
        $configFile = $this->getFullPath($filename, $source);
        $configArray = $this->getConfigArray($configFile);

        return $this->getTheConfig($configArray, $values);
    }

    function splitValues($config)
    {
        $exp = explode('.', $config);

        $elements['filename'] = $exp[0];

        unset($exp[0]);

        foreach ($exp as $param)
            $elements[] = $param;

        return $elements;
    }

    function getFilename($values)
    {
        return $values['filename'];
    }

    function getFullPath($filename, $source)
    {
        return $source . '/' . $filename . '.php';
    }

    function getConfigArray($configFile)
    {
        if(file_exists($configFile))
            return require $configFile;
    }

    function getTheConfig($configArray, $values)
    {
        $config = null;

        for($i = 0; $i < count($values) - 1; $i++)
        {
            if($i == 0)
                $config = $configArray[$values[$i]];
            else
                $config = $config[$values[$i]];
        }

        return $config;
    }
}