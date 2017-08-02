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
        $params = $this->splitParams($config);
        $filename = $this->getFilename($params);
        $configFile = $this->getFullPath($filename, $source);
        $configArray = $this->getConfigArray($configFile);

        return $this->getTheConfig($configArray, $params);
    }

    function splitParams($config)
    {
        $exp = explode('.', $config);

        $elements['filename'] = $exp[0];

        unset($exp[0]);

        foreach ($exp as $param)
            $elements[] = $param;

        return $elements;
    }

    function getFilename($params)
    {
        return $params['filename'];
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

    function getTheConfig($configArray, $params)
    {
        $config = null;

        for($i = 0; $i < count($params) - 1; $i++)
        {
            if($i == 0)
                $config = $configArray[$params[$i]];
            else
                $config = $config[$params[$i]];
        }

        return $config;
    }
}