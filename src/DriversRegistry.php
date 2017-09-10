<?php

namespace Unity\Component\Config;

use Unity\Component\Config\Contracts\IFileDriver;

class DriversRegistry
{
    /**
     * Drivers ALIASES
     */
    const ALIASES = [
        'php' =>  Drivers\File\PhpDriver::class,
        'ini' =>  Drivers\File\IniDriver::class,
        'json' => Drivers\File\JsonDriver::class,
        'yml' =>  Drivers\File\YamlDriver::class
    ];

    /** Drivers supported extension */
    const SUPPORTEDEXTS = [
        'php' => ['php', 'inc'],
        'ini' => ['ini'],
        'json' =>['json'],
        'yml' => ['yml', 'yaml']
    ];

    /**
     * Returns all available drivers
     *
     * @return array
     */
    function getDrivers()
    {
        return self::ALIASES;
    }

    /**
     * Returns all file extensions supported by the driver
     *
     * @return array
     */
    function getDriversExts()
    {
        return self::SUPPORTEDEXTS;
    }

    /**
     * Checks if a driver with the given alias exists
     *
     * @param $alias
     * @return bool
     */
    function hasAlias($alias)
    {
        return isset(self::ALIASES[$alias]);
    }

    /**
     * Gets the driver instance associated to the given alias
     *
     * @param $alias
     * @return string
     */
    function getFromAlias($alias)
    {
        if($this->hasAlias($alias))
            return self::ALIASES[$alias];
    }

    /**
     * Returns an IFileDriver instance that supports
     * the given extension
     *
     * @param $ext string
     *
     * @return IFileDriver|null
     */
    function getFromExt($ext)
    {
        $drivers = $this->getDrivers();

        foreach ($drivers as $alias => $driver) {
            if($this->driverHasExt($alias, $ext))
                return $driver;
        }
    }

    /**
     * Returns all file extensions supported by the $driver
     *
     * @param $alias string Driver alias
     * @return array
     */
    function getDriverSUPPORTEDEXTS($alias)
    {
        return self::SUPPORTEDEXTS[$alias];
    }

    /**
     * Checks if the FileDriver associated with the $driverAlias supports the
     * given extension
     *
     * @param $ext string File extension
     * @param $driverAlias string Driver alias
     *
     * @return bool
     */
    function driverHasExt($driverAlias, $ext)
    {
        $driverSUPPORTEDEXTS = $this->getDriverSUPPORTEDEXTS($driverAlias);

        foreach ($driverSUPPORTEDEXTS as $driverSupportedExt)
            if($ext == $driverSupportedExt)
                return true;

        return false;
    }

    /**
     * Checks if a driver supports the given extension
     *
     * @param $ext
     * @return bool
     */
    function driverSupportsExt($ext)
    {
        $aliases = array_keys($this->getDrivers());

        foreach ($aliases as $alias)
            if($this->driverHasExt($alias, $ext))
                return true;

        return false;
    }
}
