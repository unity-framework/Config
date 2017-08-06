<?php

namespace Unity\Component\Config\Drivers\File;

use Unity\Component\Config\Drivers\Driver;

abstract class FileDriver extends Driver
{
    /** @var string */
    protected $ext = '';

    /**
     * Sets the extension of config files
     *
     * @param $ext
     */
    function setExt($ext)
    {
        $this->ext = ltrim($ext, '.');
    }

    /**
     * Gets the extension of config files
     *
     * @return string
     */
    function getExt()
    {
        return $this->ext;
    }

    /**
     * Checks if $filename file exists
     *
     * @param $configFile
     * @return bool
     */
    function fileExists($configFile)
    {
        return file_exists($configFile);
    }

    /**
     * Gets the filename with extension
     *
     * @param $filename
     * @return string
     */
    function getFilenameWithExt($filename)
    {
        return $filename . '.' . $this->getExt();
    }

    /**
     * Returns the full path to access the
     * configuration file based in the $source
     *
     * @param $filename
     * @param $source
     * @return string
     */
    function getFullPath($filename, $source)
    {
        return $source . $this->getFilenameWithExt($filename);
    }
}