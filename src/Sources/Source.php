<?php

namespace Unity\Component\Config;

use Unity\Component\Config\Contracts\ISource;

/**
 * Class Source.
 *
 * Represents a configuration data source.
 * 
 * @author Eleandro Duzentos <eleandro@inbox.ru|github:e200>
 */
class Source implements ISource
{
    /**
     * @var string It's basically the source identifier
     */
    protected $key;

    /**
     * @var mixed Contains or gives a way to access configurations data.
     */
    protected $source;

    /**
     * @var string Alias of the driver that can parse
     * the $source content as an array
     */
    protected $driverAlias;

    /**
     * @var ContainerInterface Where all drivers dependencies are stored
     */
    protected $container;

    /**
     * Source constructor.
     *
     * @param ContainerInterface $container
     */
    function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Returns an array containing all configurations data.
     *
     * @return array
     */
    function getData()
    {
        $source      = $this->getSource();
        $driverAlias = $this->getDriverAlias();

        return $this->container->get($driverAlias)->load($source);
    }

    /**
     * @return mixed
     */
    function getSource()
    {
        return $this->source;
    }

    /**
     * @return string
     */
    function getKey()
    {
        return $this->key;
    }

    /**
     * @return bool
     */
    function hasKey()
    {
        return !is_null($this->key);
    }

    /**
     * @return string
     */
    function getDriverAlias()
    {
        return $this->driverAlias;
    }
}
