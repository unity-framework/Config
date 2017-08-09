<?php

namespace Unity\Component\Config\Drivers;

use Unity\Component\Config\Drivers\File\Exceptions\ConfigNotFoundException;
use Unity\Component\Config\Drivers\File\Exceptions\InvalidConfigStringException;

abstract class Driver implements DriverInterface
{
    /**
     * Gets the configuration
     *
     * @param string $config The required configuration
     * @param $source
     * @return mixed
     * @throws ConfigNotFoundException
     */
    function get($config, $source)
    {
        /**
         * Gets the `$root` and the `$searchKeys`
         * from the `$config` notation
         */
        $this->denote($config, $root, $searchKeys);

        /**
         * Gets the configuration array calling
         * the `Implementor::resolve()` method
         */
        $configArray = $this->getConfigArray($root, $source);

        /**
         * Returns the configuration value that
         * matches the `$config` notation
         */
        $configValue = $this->getConfigValue($configArray, $searchKeys);

        /**
         * If $config is empty, that means no
         * configuration was found
         */
        if(empty($configValue))
            throw new ConfigNotFoundException("Cannot find configuration \"$config\"");

        return $configValue;
    }

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
    function getConfigValue($configArray, $searchKeys)
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
                $config = $this->search($searchKeys[$i], $configArray);
            else
                $config = $this->search($searchKeys[$i], $config);
        }

        return $config;
    }

    /**
     * Search for a key on the `$configArray`
     *
     * @param $searchKey
     * @param $configArray
     * @return mixed
     */
    function search($searchKey, $configArray)
    {
        if(isset($configArray[$searchKey]))
            return $configArray[$searchKey];
    }
}
