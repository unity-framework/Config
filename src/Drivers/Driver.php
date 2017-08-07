<?php

namespace Unity\Component\Config\Drivers;

use Unity\Component\Config\Drivers\File\Exceptions\InvalidConfigStringException;

abstract class Driver implements DriverInterface
{
    /**
     * Denotes the config string using dot notation
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

        /**
         * Unsetting the first element,
         * we keep only the keys
         */
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
     * Where `database` is the root entry
     * and `user` is the key
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

    /**
     * Do the job of get the configuration
     * value based on `$searchKeys`
     *
     * @param $configArray array Array containing
     * the configuration value
     *
     * @param $searchKeys array Array containing the
     * keys to access the configuration value
     *
     * @return null|mixed
     */
    function getConfig($configArray, $searchKeys)
    {
        $config = null;

        /**
         * If `$searchKeys` contains only
         * one key, we just return the
         * `$configArray` value associated to that key
         *
         * If `$searchKeys` contains more then
         * one key, we access and store the first
         * `$configArray` value (it must be an array)
         * to the first `$searchKey`, and do the same
         * with the rest of keys until they finish,
         * the last `$searchKey` contains our value
         */
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