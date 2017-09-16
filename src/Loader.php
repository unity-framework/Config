<?php

namespace Unity\Component\Config;

/**
 * Class Loader.
 *
 * Loads all availables configurations.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 */
class Loader
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Loads the configurations in $source.
     *
     * @param $source string|array
     *    A source or a collection of sources.
     *
     *    A source is where the configurations are stored.
     *
     *    A source can be a (file|folder), an array
     *    containing (files|folders) or a string.
     * @param $ext string
     *    Extension for source files.
     *
     *    Used only with source files, ignored otherwise.
     *
     *    Setting $ext, will filter and load only files that
     *    matchs this extension.
     * @param $driverAlias string
     *    Driver alias
     *
     *    Setting the $driverAlias will filter and load only sources
     *    supported by the driver associated to this $driverAlias.
     *
     * @return mixed
     */
    public function load($source, $ext, $driverAlias)
    {
        if ($this->isCacheEnabled()) {
            $cache = $this->container->get('cache');
            $sources = $this->matchSources($source, $ext, $driverAlias);

            if ($sources->hasChanges()) {
                $data = $this->fetchAndCacheData($source, $ext, $driverAlias);
            } else {
                if ($cache->isHit('parsedConfigurations')) {
                    $data = $cache->get('parsedConfigurations');
                } else {
                    $data = $this->fetchAndCacheData($source, $ext, $driverAlias);
                }
            }
        } else {
            $data = $this->fetchData($source, $ext, $driverAlias);
        }

        return $data;
    }

    public function matchSources($source, $ext, $driverAlias)
    {
        return $this->container->get('sourcesMatcher')->match($source, $ext, $driverAlias);
    }

    public function fetchData($sources)
    {
        return $sources->collectData();
    }

    public function fetchAndCacheData($sources)
    {
        $data = $this->fetchData($sources);

        $cache->set('parsedConfigurations', $data);

        return $data;
    }

    /**
     * Checks if cache is enabled.
     *
     * @return bool
     */
    public function isCacheEnabled()
    {
        return $this->container->has('configCache');
    }
}
