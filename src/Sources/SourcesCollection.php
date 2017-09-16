<?php

namespace Unity\Component\Config;

use Unity\Component\Config\Contracts\ISource;

/**
 * Class SourcesCollection.
 *
 * Contains a collection of sources for configurations data.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru|github:e200>
 */
class SourcesCollection
{
    protected $sources = [];
    protected $container;

    function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Adds ISource instances to the collection
     *
     * @param $source ISource[]|ISource
     */
    function add($source)
    {
        if (is_array($source)) {
            foreach ($source as $src) {
                $this->addIfValid($src);
            }
        }

        $this->addIfValid($source);
    }

    /**
     * Only adds the source to the collection if the
     * sources implements the ISource Contract.
     *
     * @param $source ISource
     */
    function addIfValid($source)
    {
        $this->validate($source);

        $this->sources[] = $source;
    }

    /**
     * Ensures that $source implements ISource contract.
     *
     * @param $source ISource
     */
    function validate(ISource $source)
    {
        //This method is as it is.
    }

    /**
     * Joins all configurations data from each
     * source in the collection.
     */
    function joinData()
    {
        $data = [];

        foreach ($this->getAll() as $source) {
            if ($source->hasKey()) {
                $data[$source->getKey()] = $source->getData();
            } else {
                $data[] = $source->getData();
            }
        }

        return $data;
    }

    /**
     * Collects and returns an array containing all configurations data
     *
     * @return array
     */
    function collectData()
    {
        return $this->joinData();
    }

    /**
     * Checks if at least one source was changed after
     * we cached our configurations data.
     *
     * Why? 'Cause, if any configuration sources has recents
     * changes then our old cached data are invalid.
     *
     * @return bool
     */
    function hasChanges()
    {
        $lastCacheTime = $this->container->configCache->lastCacheTime();

        foreach ($this->getAll() as $source) {
            if (filemtime($source->get()) > $lastCacheTime) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns all supported and available sources.
     *
     * @return ISource[]
     */
    function getAll()
    {
        return $this->sources;
    }
}
