<?php

namespace Unity\Component\Config;

use Unity\Component\Config\Contracts\IFileDriver;
use Unity\Support\File;

/**
 * Class DriversRegistry.
 *
 * Contains registry about available drivers.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru|github:e200>
 */
class DriversRegistry
{
    /** Drivers aliases and their supported extensions */
    const DRIVERSALIASESANDEXT = [
        'php' => ['php', 'inc'],
        'ini' => ['ini'],
        'json' =>['json'],
        'yml' => ['yml', 'yaml']
    ];

    /**
     * Returns all drivers aliases and their
     * supported extensions
     *
     * @return array
     */
    function getDrivers()
    {
        return self::DRIVERSALIASESANDEXT;
    }

    /**
     * Returns all supported extensions associated
     * with the given $driverAlias
     *
     * @param $driverAlias
     *
     * @return array
     */
    function getSupportedExt($driverAlias)
    {
        return self::DRIVERSALIASESANDEXT[$driverAlias];
    }

    /**
     * Checks if a driver with the given alias exists
     *
     * @param $alias
     * @return bool
     */
    function hasAlias($alias)
    {
        return isset(self::DRIVERSALIASESANDEXT[$alias]);
    }

    /**
     * Returns the driver alias for the driver
     * that supports the given extension
     *
     * @param $ext string
     *
     * @return string|false
     */
    function getFromExt($ext)
    {
        $aliases = array_keys($this->getDrivers());

        foreach ($aliases as $alias) {
            if ($this->driverSupportsExt($alias, $ext)) {
                return $driver;
            }
        }

        return false;
    }

    /**
     * Returns an IFileDriver instance that supports
     * the given $file extension
     *
     * @param $filename string
     *
     * @return string|false
     */
    function getFromFile($filename)
    {
        $ext = File::ext($filename);

        return $this->getFromExt($ext);
    }

    /**
     * Checks if the FileDriver associated with the
     * $driverAlias supports the given extension
     *
     * @param $ext string File extension
     * @param $alias string Driver alias
     *
     * @return bool
     */
    function driverSupportsExt($alias, $ext)
    {
        $sExtensions = $this->getSupportedExt($alias);

        foreach ($sExtensions as $sExtension) {
            if ($ext == $sExtension) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if this class has a driver
     * that supports the given $ext
     *
     * @param $ext string File extension
     *
     * @return bool
     */
    function hasDriverSupportForThisExt($ext)
    {
        $driversAliases = $this->getDrivers();

        foreach ($driversAliases as $driverAlias)
        {
            if ($this->driverSupportsExt($driverAlias, $ext)) {
                return true;
            }
        }
    }
}
