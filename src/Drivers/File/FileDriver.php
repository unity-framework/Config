<?php

namespace Unity\Component\Config\Drivers\File;

use Unity\Component\Config\Drivers\Driver;
use Unity\Component\Config\Drivers\File\Exceptions\ConfigNotFoundException;

abstract class FileDriver extends Driver implements FileDriverInterface
{
    /** @var string */
    protected $ext;

    /** @var string  */
    private $resolverMethod = 'resolve';

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
     * Checks if FileDriver::$ext is defined
     *
     * @return bool
     */
    function hasExt()
    {
        return !empty($this->ext);
    }

    /**
     * Gets the configuration array containing
     * in the file
     *
     * @param $filename
     * @param $source
     * @return mixed
     * @throws ConfigNotFoundException
     */
    function getConfigArray($filename, $source)
    {
        $configArray = null;

        /**
         * If `$source is an array, search
         * the `$filename` in each `$source`
         */
        if(is_array($source))
            foreach ($source as $src) {
                $configArray = $this->callResolver($filename, $src);

                /** If was found a configuration, stop searching */
                if($configArray)
                    break;
            }
        else
            $configArray = $this->callResolver($filename, $source);

        /** If was found a configuration, return it */
        if($configArray)
            return $configArray;
    }

    /**
     * Calls the `Implementor::resolve()` method
     *
     * @param $filename
     * @param $src
     * @return mixed
     */
    function callResolver($filename, $src)
    {
        $file = $this->getFile($filename, $src);

        return call_user_func_array(
            [
                $this,
                $this->getResolverMethod()
            ],
            [$file]
        );
    }

    /**
     * Returns the `Implementor` resolver method name
     *
     * @return string
     */
    function getResolverMethod()
    {
        return $this->resolverMethod;
    }

    /**
     * Returns the full path to access the
     * configuration file based in a `$source`
     *
     * @param $filename
     * @param $source
     * @return string
     */
    function getFile($filename, $source)
    {
        return $source . $this->getFilenameWithExt($filename);
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
}
