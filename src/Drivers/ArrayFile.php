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
        $exp = explode('.', $config);

        $filename = $exp[0];
        $array_key = $exp[1];

        $array = require $source . '/' . $filename . '.php';

        return $array[$array_key];
    }
}