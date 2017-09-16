<?php

namespace Unity\Component\Config\Drivers;

use Unity\Component\Config\Contracts\IFileDriver;

/**
 * class FileDriver.
 *
 * Abstract class that implements IFileDriver interface.
 *
 * Implements IFileDriver interface.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru|github:e200>
 */
abstract class FileDriver extends Driver implements IFileDriver
{
    /**
     * Returns all configurations data
     *
     * @param mixed $file string The file containing the configurations
     *
     * @return array
     */
    function load($file) : array
    {
        return $this->parse($file);
    }
}
