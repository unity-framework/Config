<?php

namespace Unity\Component\Config\Contracts;

use Unity\Component\Container\Contracts\IContainer;

/**
 * Interface IConfigManager.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 */
interface IConfigManager
{
    /**
     * Sets the config source.
     *
     * @param string $source
     *
     * @return static
     */
    public function setSource($source);

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
    public function setExt($ext);

    /**
     * Sets the driver to be used when
     * trying retrieve configs data.
     *
     * @param string $alias
     *
     * @return static
     */
    public function setDriver($alias);

    /**
     * Sets the DI container.
     *
     * @param IContainer $container
     *
     * @return static
     */
    public function setContainer(IContainer $container);

    /**
     * Enables or disables configs modifications.
     *
     * @param bool $enable
     *
     * @return static
     */
    public function allowModifications($enable);

    /**
     * Checks if a source was provided.
     *
     * @return bool
     */
    public function hasSource();

    /**
     * Sets the DI container.
     *
     * @return bool
     */
    public function hasContainer();

    /**
     * Setups the cache.
     *
     * @param string $cachePath
     * @param string $cacheExpTime
     * @param bool   $allowModifications
     *
     * @return static
     */
    public function setupCache($cachePath, $cacheExpTime = null);

    /**
     * Checks if the cache is enabled.
     *
     * @return bool
     */
    public function isCacheEnabled();
}
