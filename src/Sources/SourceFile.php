<?php

namespace Unity\Component\Config\Sources;

use Unity\Component\Config\Contracts\Drivers\IDriver;
use Unity\Component\Config\Contracts\Sources\ISourceFile;

/**
 * Class SourceFile.
 *
 * Represents a config file source.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 *
 * @link   https://github.com/e200/
 */
class SourceFile implements ISourceFile
{
    /**
     * @var string Source identifier.
     */
    protected $key;

    /**
     * @var mixed Contains or gives a way to access configs data.
     */
    protected $source;

    /**
     * @var string Alias of the driver that can parse
     *             the `$source` content as an array
     */
    protected $driver;

    /**
     * Sources constructor.
     *
     * @param string  $key
     * @param string  $source
     * @param IDriver $driver
     */
    public function __construct($key, $source, IDriver $driver)
    {
        $this->key = $key;
        $this->source = $source;
        $this->driver = $driver;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Returns an array containing all configs on this file.
     *
     * @return array
     */
    public function getData()
    {
        return $this->driver->load($this->source);
    }
}
