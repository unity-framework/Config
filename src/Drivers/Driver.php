<?php

namespace Unity\Component\Config\Drivers;

use Unity\Component\Config\Drivers\ArrayFile\Exceptions\InvalidConfigStringException;

abstract class Driver implements DriverInterface
{
    /**
     * Denotes the config string dot notation
     *
     * @param $config
     * @param $root
     * @param $keys
     * @throws InvalidConfigStringException
     */
    function denote($config, &$root, &$keys)
    {
        $this->validate($config);

        $exp = explode('.', $config);

        $root = $exp[0];

        unset($exp[0]);

        foreach ($exp as $param)
            $keys[] = $param;
    }

    /**
     * Validates the given notation
     *
     * A valid notation must have a root entry
     * followed by at least one key.
     *
     * Example: database.user
     *
     * @param $notation
     * @throws InvalidConfigStringException
     */
    function validate($notation)
    {
       if(!preg_match('/\w{1,}\.(\w{1,}){1,}/', $notation))
            throw new InvalidConfigStringException(
                "The config string must have a root entry with at least one key.
                \nExample: database.user.
                \nWhere \"database\" is the root entry and \"user\" is the key."
            );
    }

    function getConfig($configArray, $searchKeys)
    {
        $config = null;

        for($i = 0; $i < count($searchKeys); $i++)
        {
            if($i == 0)
                $config = $configArray[$searchKeys[$i]];
            else
                $config = $config[$searchKeys[$i]];
        }
        return $config;
    }
}