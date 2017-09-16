<?php

namespace Unity\Component\Config\Resolvers;

use Unity\Component\Config\DriversRegistry;
use Unity\Support\File;

class FileSourceResolver
{
    protected $driversRegistry;

    public function __construct(DriversRegistry $driversRegistry)
    {
        $this->driversRegistry = $driversRegistry;
    }

    /**
     * @param $src
     * @param $filename
     * @param $ext
     * @param $driver
     *
     * @return string|null
     */
    public function resolve($src, $filename, $ext, $driver)
    {
        /*
         * If our source is an explicit file
         */
        if (is_file($src)) {
            return $src;
        }

        /*
         * If our source is a folder
         */
        if (is_dir($src)) {
            return $this->getSourceFileFromFolder(
                $filename,
                $src,
                $ext,
                $driver
            );
        }
    }

    /**
     * Resolves the $source based on the provided
     * information contained in the $root, $ext, $driver vars.
     *
     * @param $filename string|array Configuration filename
     *
     * Used in case of no explicit source file declared
     * @param $folder string Configuration source
     *
     * Can be:A file, A directory path,
     * an array containing various files,
     * an array containing various folders
     * @param $ext string Configuration file extension
     *
     * Used in case of no explicit source file extension declared
     *
     * This prevents the automatic search of source file
     * extension providing explicitly its extension
     * name (performance purposes)
     * @param $driver string Driver alias
     *
     * This prevents the automatic search of
     * the driver that supports the source file
     * providing explicitly its driver alias
     * (performance purposes)
     *
     * @return string|null
     */
    public function getSourceFileFromFolder($filename, $folder, $ext, $driver)
    {
        $searchPattern = $this->getSearchPattern($filename, $ext);

        $files = $this->matchFilesInFolder($folder, $searchPattern);

        return $this->getSupportedFile($files, $driver);
    }

    /**
     * Returns the search file in folder pattern.
     *
     * @param $filename string
     * @param string $ext
     *
     * @return string
     */
    public function getSearchPattern($filename, $ext = null)
    {
        return $filename.'.'.($ext ? $ext : '*');
    }

    /**
     * Gets all file names in `$folder`
     * that matches the `$searchPattern`.
     *
     * @param $folder
     * @param $searchPattern
     *
     * @return array Contains matched files
     */
    public function matchFilesInFolder($folder, $searchPattern)
    {
        return $this->glob($folder, $searchPattern);
    }

    /**
     * Searches for a file that has a driver that
     * supports it.
     *
     * @param $files array Contains the file names
     * to looking for
     * @param $driver
     *
     * @return string
     */
    public function getSupportedFile($files, $driver = null)
    {
        foreach ($files as $file) {
            $ext = File::ext($file);

            if (is_null($driver)) {
                if ($this->driversRegistry->driverSupportsExt($ext)) {
                    return $file;
                }
            } else {
                if ($this->driversRegistry->driverHasExt($driver, $ext)) {
                    return $file;
                }
            }
        }

        return false;
    }

    /**
     * Glob that is safe with streams (vfs for example).
     *
     * @param string $dir
     * @param string $searchPattern
     *
     * @return array
     *
     * @see https://github.com/mikey179/vfsStream/issues/2#issuecomment-252271019
     */
    public function glob($dir, $searchPattern)
    {
        $files = scandir($dir);
        $found = [];

        foreach ($files as $filename) {
            if (in_array($filename, ['.', '..'])) {
                continue;
            }

            if (fnmatch($searchPattern, $filename)) {
                $found[] = $dir.DIRECTORY_SEPARATOR.$filename;
            }
        }

        return $found;
    }
}
