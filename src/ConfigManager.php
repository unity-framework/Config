<?php

namespace Unity\Component\Config;

use Unity\Component\Config\Exceptions\InvalidSourceException;
use Unity\Component\Container\ContainerManager;
use Unity\Contracts\Config\IConfigManager;
use Unity\Contracts\Config\ILoader;
use Unity\Contracts\Config\Sources\ISourceCache;
use Unity\Contracts\Container\IContainer;

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
    protected $readOnlyMode = true;

    /** @var IContainer */
    protected $container;

    /**
     * Sets the configuration source.
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
     * Sets the extension for configuration(s) files.
     *
     * Useful if your configuration
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
        $this->ext = $ext;

        return $this;
    }

    /**
     * Sets the Driver to be used when
     * trying retrieve configurations data.
     *
     * @param string $driver
     *
     * @return static
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
     * Enable or disable configurations
     * read only mode.
     *
     * @param bool $enabled
     *
     * @return static
     */
    public function readOnlyMode($enabled)
    {
        $this->readOnlyMode = $enabled;

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
     * Sets the cache path.
     *
     * It's also actives the caching.
     *
     * @param string $cachePath
     * @param string $cacheExpTime
     *
     * @return static
     */
    public function setupCache($cachePath, $cacheExpTime)
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
    protected function setUpContainer()
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
            throw new InvalidSourceException('Invalid source provided.');
        }

        $source = $this->source;
        $driver = $this->driver;
        $ext = $this->ext;
        $cachePath = $this->cachePath;
        $cacheExpTime = $this->cacheExpTime;

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

        return $this->container->make('config', [$data]);
    }
}
