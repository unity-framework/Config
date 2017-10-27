<?php

namespace Unity\Component\Config;

use Psr\Container\ContainerInterface;
use Unity\Contracts\Container\IContainer;
use Unity\Component\Container\ContainerBuilder;

class ConfigBuilder
{
    /** @var string */
    protected $ext;

    /** @var string */
    protected $driver;

    /** @var string */
    protected $source;
    
    /** @var string */
    protected $cachePath;
        
    /** @var string */
    protected $cacheExpTime;

    /** @var IContainer */
    protected $container;

    /**
     * Sets the configuration source.
     *
     * @param mixed $source
     *
     * @return ConfigBuilder
     */
    public function setSource($source)
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
    public function setExt($ext)
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
    public function setDriver($driver)
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * Sets the DI container.
     *
     * @param IContainer $container
     *
     * @return static
     */
    public function setContainer(IContainer $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * Gets the DI container.
     *
     * @return IContainer
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Sets the DI container.
     *
     * @return bool
     */
    public function hasContainer()
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
     * @return static
     */
    public function setCachePath($path)
    {
        $this->cachePath = $path;

        return $this;
    }

    /**
     * Returns the cache path.
     */
    public function getCachePath()
    {
        return $this->cachePath;
    }

    /**
     * Checks if cache is enabled.
     *
     * @return bool
     */
    public function canCache()
    {
        return !is_null($this->cachePath);
    }

    /**
     * Builds and returns a new instance of `Config::class`.
     *
     * @return Config
     */
    public function build()
    {
        $source = $this->source;
        $driver = $this->driver;
        $ext = $this->ext;

        $cache = null;

        $data = [];

        if (!$this->hasContainer()) {
            $container = (new ContainerBuilder())->build();

            $container->setServiceProvider(new ConfigServiceProvider());

            $this->setContainer($container);
        }

        $container = $this->getContainer();

        $loader = $container->loader;

        if ($this->canCache()) {
            $cache = $container->make('sourceCache', [
                $source,
                $this->cachePath,
                $this->cacheExpTime
            ]);

            if (!$cache->isExpired() && !$cache->hasChanges()) {
                $data = $cache->get();
            } else {
                $data = $loader->load($source, $driver, $ext);

                $cache->set($data);
            }
        } else {
            $data = $loader->load($source, $driver, $ext);
        }

        return $container->make('config', [$data]);
    }
}
