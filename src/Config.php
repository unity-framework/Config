<?php

namespace Unity\Component\Config;

use Unity\Support\Arr;
use Unity\Component\Config\Contracts\IConfig;
use Unity\Component\Config\Contracts\INotation;
use Unity\Component\Config\Drivers\DriverFactory;
use Unity\Component\Config\Exceptions\ConfigNotFoundException;
use Unity\Component\Config\Notation\NotationBag;
use Unity\Component\Config\Notation\DotNotation;
use Unity\Component\Config\Matcher\SourceMatcher;
use Unity\Component\Config\DriversRegistry as Drivers;

/**
 * Class Config
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 */
class Config implements IConfig
{
    protected $src;
    protected $ext;
    protected $driver;
    protected $sourceResolver;
    protected $cache = [];

    /**
     * Config constructor.
     *
     * @param $src mixed Configuration source
     * @param string|null $ext Configuration file extension, for file sources
     * @param string|null $driver The alias to the driver to be used
     */
    function __construct($src, $ext = null, $driver = null)
    {
        $this->src    = $src;
        $this->ext    = $ext;
        $this->driver = $driver;
    }

    /**
     * Gets a configuration value
     *
     * @param $config
     *
     * @return mixed
     */
    function get($config)
    {
        $notation = DotNotation::denote($config);

        $root = $notation->getRoot();

        if ($this->isCached($root)) {
            $data = $this->getCache($root);
        } else {
            $data = $this->getData($root);

            $this->setCache($root, $data);
        }

        if ($notation->hasKeys()) {
            $keys = $notation->getKeys();

            return Arr::nestedGet($keys, $data);
        } else {
            return $data[$root];
        }
    }

    /**
     * Checks ifs a configuration exists
     *
     * @param $config
     *
     * @return bool
     */
    function has($config)
    {
        $notation = DotNotation::denote($config);

        $root = $notation->getRoot();
        $keys = $notation->getKeys();

        if ($this->isCached($root)) {
            $data = $this->getCache($root);
        } else {
            $data = $this->getData($root);

            $this->setCache($root, $data);
        }

        return Arr::nestedHas($keys, $data);
    }

    function isCached($config)
    {
        return isset($this->cache[$config]);
    }

    function setCache($key, $data)
    {
        $this->cache[$key] = $data;
    }

    function getCache($key)
    {
        return $this->cache[$key];
    }

    function getData($root)
    {
        $source      = $this->getSource();
        $ext         = $this->getExt();
        $driverAlias = $this->getDriverAlias();

        $drivers = $this->getDrivers();

        $src = $this->getSourceMatcher($drivers)->match(
                $source,
                $root,
                $ext,
                $driverAlias
            );

        if($src->isEmpty())
            throw new ConfigNotFoundException("Cannot find the requested configuration");

        $driverFactory = $this->getDriverFactory($drivers);

        if($this->hasDriverAlias())
            $driver = $driverFactory->makeFromAlias($driverAlias);
        elseif($this->hasExt())
            $driver = $driverFactory->makeFromExt($ext);
        elseif($src->isFile())
            $driver = $driverFactory->makeFromFile($src->get());

        return $driver->load($src->get());
    }

    /**
     * Returns a DriversRegistry instance
     *
     * @return DriversRegistry
     */
    function getDrivers()
    {
        return new Drivers;
    }

    function getSourceMatcher($drivers)
    {
        return new SourceMatcher($drivers);
    }

    function getDriverFactory($drivers)
    {
        return new DriverFactory($drivers);
    }

    /**
     * @return bool
     */
    function hasDriverAlias()
    {
        return !is_null($this->driver);
    }

    /**
     * @return string|null
     */
    function getDriverAlias()
    {
        return $this->driver;
    }

    /**
     * Checks if there's an extension
     * @return bool
     */
    function hasExt()
    {
        return !is_null($this->ext);
    }

    /**
     * @return string|null
     */
    function getExt()
    {
        return $this->ext;
    }

    /**
     * @return mixed
     */
    function getSource()
    {
        return $this->src;
    }
}
