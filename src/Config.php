<?php

/*
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 */

namespace Unity\Component\Config;

use Unity\Component\Config\Contracts\IDriver;
use Unity\Component\Config\Drivers\DriverFactory;
use Unity\Component\Config\Notation\DotNotation;
use Unity\Component\Config\Resolvers\SourceResolver;

class Config
{
    protected $src;
    protected $ext;
    protected $driver;
    protected $sourceResolver;

    /**
     * Config constructor.
     *
     * @param $src mixed Configuration source
     * @param string|null $ext    Configuration file extension, for file sources
     * @param string|null $driver The alias to the driver to be used
     */
    public function __construct($src, $ext = null, $driver = null)
    {
        $this->src = $src;
        $this->ext = $ext;
        $this->driver = $driver;
    }

    /**
     * Returns a DriversRegistry instance.
     *
     * @return DriversRegistry
     */
    public function getDriversRegistry()
    {
        return new DriversRegistry();
    }

    /**
     * Checks ifs a configuration exists.
     *
     * @param $config
     */
    public function has(/*$config*/)
    {
    }

    /**
     * Gets a configuration value.
     *
     * @param $config
     *
     * @return mixed
     */
    public function get($config)
    {
        $notation = DotNotation::denote($config);

        $src = $this->getSource();
        $root = $notation->getRoot();
        $keys = $notation->getKeys();
        $ext = $this->getExt();
        $driverAlias = $this->getDriverAlias();

        $driversRegistry = $this->getDriversRegistry();

        $src = (new SourceResolver($driversRegistry))->resolve(
            $src,
            $root,
            $ext,
            $driverAlias
        );

        $driverFactory = new DriverFactory($driversRegistry);

        /*
         * If the ConfigBuilder class don't explicitly the
         * driver to be used, we must try auto locate a driver
         * based on the `$src` NOR `$root` arguments
         */
        if ($this->hasDriverAlias()) {
            $driver = $driverFactory->makeFromAlias($driverAlias);
        } elseif ($this->hasExt()) {
            $driver = $driverFactory->makeFromExt($ext);
        } else {
            $driver = $driverFactory->makeFromFile($src);
        }

        $driver->setSource($src);

        return $driver->get($keys);
    }

    /**
     * @return bool
     */
    public function hasDriverAlias()
    {
        return !is_null($this->driver);
    }

    /**
     * @return IDriver|null
     */
    public function getDriverAlias()
    {
        return $this->driver;
    }

    /**
     * Checks if there's an extension.
     *
     * @return bool
     */
    public function hasExt()
    {
        return !is_null($this->ext);
    }

    /**
     * @return string|null
     */
    public function getExt()
    {
        return $this->ext;
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->src;
    }
}
