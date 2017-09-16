<?php

namespace Unity\Component\Config\Resolvers;

use Unity\Support\File;
use Unity\Component\Config\DriversRegistry;

class SourceResolver
{
    protected $driversRepository;

    function __construct(DriversRegistry $driversRepository)
    {
        $this->driversRepository = $driversRepository;
    }

    /**
     * Resolves the $source based on the provided
     * information contained in the $root, $ext, $driver vars
     *
     * @param $source string|array Configuration source
     *
     * Can be: A file, A directory path,
     * an array containing various files,
     * an array containing various folders
     *
     *
     * @param $root string Configuration filename
     *
     * Used in case of no explicit source file declared
     *
     * @param $ext string Configuration file extension
     *
     * Used in case of no explicit source file extension declared
     *
     * This prevents the automatic search of source file
     * extension providing explicitly its extension
     * name (performance purposes)
     *
     * @param $driver string Driver alias
     *
     * This prevents the automatic search of
     * the driver that supports the source file
     * providing explicitly its driver alias
     * (performance purposes)
     *
     * @return null|string
     */
    function resolve($source, $root, $ext, $driver)
    {
        /** If our source is an array, resolve each source containing on it */
        if(is_array($source))
            foreach ($source as $src) {
                $file = $this->reusedCode($src, $root, $ext, $driver);

                if($file)
                    return $file;
            }
        else
            return $this->reusedCode($source, $root, $ext, $driver);
    }

    function reusedCode($src, $root, $ext, $driver)
    {
        if (File::exists($src))
            return (new FileSourceResolver($this->driversRepository))
                ->resolve($src, $root, $ext, $driver);
    }
}
