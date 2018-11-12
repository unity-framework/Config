<?php

namespace Unity\Component\Config;

use Unity\Component\Config\Exceptions\InvalidSourceException;
use Unity\Component\Container\ContainerManager;
use Unity\Component\Config\Contracts\IConfigManager;
use Unity\Component\Config\Contracts\ILoader;
use Unity\Component\Config\Contracts\Sources\ISourceCache;
use Unity\Component\Config\Contracts\IContainer;

/**
 * Class ConfigManager.
 *
 * Config class manager.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 *
 * @link   https://github.com/e200/
 */
class ConfigManager implements IConfigManager
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

    /** @var bool */
    protected $allowModifications = false;

    /** @var IContainer */
    protected $container;

    /**
     * Sets the config source.
     *
     * @param string $source
     *
     * @return static
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Sets the extension for config(s) files.
     *
     * Useful if your config
     * file has'nt an extension, also
     * helps the auto driver detection
     * being fast.
     *
     * @param mixed $ext
     *
     * @return static
     */
    public function setExt($ext)
    {
        $this->ext = str_replace('.', '', $ext);

        return $this;
    }

    /**
     * Sets the driver to be used when
     * trying retrieve configs data.
     *
     * @param string $alias
     *
     * @return static
     */
    public function setDriver($alias)
    {
        $this->driver = $alias;

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
     * Enables or disables configs modifications.
     *
     * @param bool $enable
     *
     * @return static
     */
    public function allowModifications($enable)
    {
        $this->allowModifications = $enable;

        return $this;
    }

    /**
     * Checks if a source was provided.
     *
     * @return bool
     */
    public function hasSource()
    {
        return !empty($this->source);
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
     * Setups the cache.
     *
     * @param string $cachePath
     * @param string $cacheExpTime
     * @param bool   $allowModifications
     *
     * @return static
     */
    public function setupCache($cachePath, $cacheExpTime = null)
    {
        $this->cachePath = $cachePath;
        $this->cacheExpTime = $cacheExpTime;

        return $this;
    }

    /**
     * Checks if the cache is enabled.
     *
     * @return bool
     */
    public function isCacheEnabled()
    {
        return !empty($this->cachePath);
    }

    /**
     * Setups the container.
     */
    protected function setupContainer()
    {
        if (!$this->hasContainer()) {
            $container = (new ContainerManager())->build();

            $container->setServiceProvider(new ConfigServiceProvider());

            $this->setContainer($container);
        }
    }

    /**
     * Returns an `ILoader` instance.
     *
     * @return ILoader
     */
    protected function getLoader()
    {
        return $this->container->get('loader');
    }

    /**
     * Returns an `ISourceCache` instance.
     *
     * @param string $source
     * @param string $cachePath
     * @param string $cacheExpTime
     *
     * @return ISourceCache
     */
    protected function getSourceCache($source, $cachePath, $cacheExpTime)
    {
        return $this->container->make('sourceCache', [
            $source,
            $cachePath,
            $cacheExpTime,
        ]);
    }

    /**
     * Setups, builds and returns a new `IConfig` instance.
     *
     * @throws InvalidSourceException
     *
     * @return Config
     */
    public function build()
    {
        if (!$this->hasSource()) {
            throw new InvalidSourceException('No source or invalid source provided.');
        }

        $source = $this->source;
        $driver = $this->driver;
        $ext = $this->ext;
        $cachePath = $this->cachePath;
        $cacheExpTime = $this->cacheExpTime;
        $allowModifications = $this->allowModifications;

        $this->setUpContainer();

        $loader = $this->getLoader();

        /*
         * TODO: Split this shit, and make it more testable.
         */
        if ($this->isCacheEnabled()) {
            $cache = $this->getSourceCache($source, $cachePath, $cacheExpTime);

            if ($cache->isHit()) {
                $data = $cache->get();
            } else {
                $data = $loader->load($source, $driver, $ext);

                $cache->set($data);
            }
        } else {
            $data = $loader->load($source, $driver, $ext);
        }

        return $this->container->make('config', [$data, $allowModifications]);
    }
}
