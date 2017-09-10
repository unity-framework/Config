<?php

namespace Unity\Component\Config\Drivers;

use  Unity\Component\Config\Contracts\IDriver;

abstract class Driver implements IDriver
{
    protected $src;

    /**
     * Returns the configuration value
     *
     * @param $keys
     *
     * @return mixed
     */
    function get($keys)
    {
        $source = $this->getSource();

        $configArray = $this->parse($source);

        return $this->getConfigValue(
            $configArray,
            $keys
        );
    }

    /**
     * Checks if a configuration exists
     *
     * @param $keys
     *
     * @return bool
     */
    function has($keys)
    {
        $configArray = $this->parse(
            $this->getSource()
        );

        return $this->hasConfigValue(
            $configArray,
            $keys
        );
    }

    /**
     * Gets the configuration value associated
     * with `$searchKeys`
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
        $count = count($searchKeys);

        /**
         * If `$searchKeys` contains only one key,
         * we just return the `$configArray` value
         * associated to that key.
         *
         * If `$searchKeys` contains more then
         * one key, we access and stores the first
         * `$configArray` value (it must be an array)
         * to the `$config`, and we do the same with
         * the remaining of keys until they finish, the
         * last `$searchKeys` contains the value
         */
        for ($i = 0; $i < $count; $i++) {
            $key = $searchKeys[$i];

            if ($i == 0)
                $config = $this->getValue($key, $configArray);
            else
                $config = $this->getValue($key, $config);
        }

        return $config;
    }

    /**
     * Checks if there's a configuration for
     * the `$searchKeys` in the configuration
     * array
     *
     * @param $configArray array Array containing
     * the configuration value
     *
     * @param $keys array Array containing the
     * keys to access the configuration value
     *
     * @return bool|mixed
     */
    function hasConfigValue($configArray, $keys)
    {
        $config = null;
        $numKeys = count($keys);

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

        for ($i = 0; $i < $numKeys; $i++) {
            $key = $keys[$i];

            if (($i + 1) == $numKeys)
                return $this->hasKey($key, $configArray);
            elseif ($i == 0)
                $config = $this->getValue($key, $configArray);
            else
                $config = $this->getValue($key, $config);
        }
    }

    /**
     * Checks if a key exists in the configuration
     * array
     *
     * @param $key
     * @param $configArray
     * @return mixed
     */
    function hasKey($key, $configArray)
    {
        return isset($configArray[$key]);
    }

    /**
     * Search for a key on the `$configArray`
     *
     * @param $key
     * @param $configArray
     * @return mixed
     */
    function getValue($key, $configArray)
    {
        if($this->hasKey($key, $configArray))
            return $configArray[$key];
    }

    /**
     * @return mixed
     */
    function getSource()
    {
        return $this->src;
    }

    /**
     * @param mixed $src
     */
    function setSource($src)
    {
        $this->src = $src;
    }
}
