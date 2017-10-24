<?php

namespace Unity\Component\Config;

use Psr\Container\ContainerInterface;
use Unity\Component\Container\ContainerBuilder;

class ConfigBuilder
{
    protected $ext;
    protected $driver;
    protected $source;
    
    protected $cachePath;

    protected $container;

    /**
     * Sets the configuration source.
     *
     * @param mixed $source
     *
     * @return ConfigBuilder
     */
    function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Sets the extension of the configuration files.
     *
     * @param mixed $ext
     *
     * @return ConfigBuilder
     */
    function setExt($ext)
    {
        $this->ext = $ext;

        return $this;
    }

    /**
     * Sets the Driver to be used to retrieve our
     * configurations data.
     *
     * @param string $driver
     *
     * @return ConfigBuilder
     */
    function setDriver($driver)
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * Sets the DI container.
     *
     * @param $container
     *
     * @return ConfigBuilder
     */
    function setContainer($container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * Gets the DI container.
     *
     * @return ContainerInterface
     */
    function getContainer()
    {
        return $this->container;
    }

    /**
     * Sets the DI container.
     *
     * @return bool
     */
    function hasContainer()
    {
        return !is_null($this->container);
    }

    /**
     * Sets the cache path.
     *
     * It's also actives the caching.
     *
     * @param $path
     *
     * @return ConfigBuilder
     */
    function setCachePath($path)
    {
        $this->cachePath = $path;

        return $this;
    }

    /**
     * Returns the cache path.
     */
    function getCachePath()
    {
        return $this->cachePath;
    }

    /**
     * Checks if cache is enabled.
     *
     * @return bool
     */
    function canCache()
    {
        return !is_null($this->cachePath);
    }

    /**
     * Builds and returns a new instance of Config class.
     *
     * @return Config
     */
    function build()
    {
        $source = $this->source;
        $driver = $this->driver;
        $ext    = $this->ext;

        $cache = null;

        $data = [];

        if (!$this->hasContainer()) {
            $container = (new ContainerBuilder)->build();

            $container->setServiceProvider(new ConfigServiceProvider());

            $this->setContainer($container);
        }

        $container = $this->getContainer();
        
        $loader = $container->loader;

        if ($this->canCache()) {
            $cache = $container->sourceCache;

            if (!$cache->isExpired($source) && !$cache->hasChanges(source)) {
                $data = $cache->get($source);
            } else {
                $data = $loader->load($source, $driver, $ext);

                $cache->set($source, $data);
            }
        } else {
            $data = $loader->load($source, $driver, $ext);
        }

        return $container->make('config', [$data]);
    }
}
