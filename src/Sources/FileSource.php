<?php

namespace Unity\Component\Config\Sources;

use Unity\Contracts\Config\Drivers\IDriver;
use Unity\Contracts\Config\Sources\IFileSource;

/**
 * Class FileSource.
 *
 * Represents a configuration file source.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 */
class FileSource implements IFileSource
{
    /**
     * @var string Source identifier.
     */
    protected $key;

    /**
     * @var mixed Contains or gives a way to access configurations data.
     */
    protected $source;

    /**
     * @var string Alias of the driver that can parse
     * the `$source` content as an array
     */
    protected $driver;

    /**
     * Sources constructor.
     *
     * @param string $key
     * @param string $source
     * @param IDriver $driver
     */
    function __construct($key, $source, IDriver $driver) {
        $this->key    = $key;
        $this->source = $source;
        $this->driver = $driver;
    }
    
    /**
     * @return string
     */
    function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    function getSource()
    {
        return $this->source;
    }

    /**
     * Returns an array containing all configurations on this file.
     *
     * @return array
     */
    function getData()
    {
        return $this->driver->parse($this->source);
    }
}
