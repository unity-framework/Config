<?php

namespace Unity\Component\Config\Matcher;

use Unity\Component\Config\Contracts\ISource;
use Unity\Component\Config\Contracts\ISourceMatcher;
use Unity\Support\File;

/**
 * Class FileSourcesMatcher.
 *
 * Matches sources files.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru|github:e200>
 */
class FileSourcesMatcher implements ISourceMatcher
{
    protected $container;

    function __construct(CotainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param $source string[]|string
     * @param $ext string
     * @param $driver string
     *
     * @return ISource[]|ISource
     */
    function match($source, $ext, $driver)
    {
        if (File::isFile($source)) {
            return $this->makeSource($source);
        }

        if (File::isDir($source)) {
            return $this->makeSourceFromFolder(
                $source,
                $ext,
                $driver
            );
        }
    }

    /**
     * Makes and returns an ISource instance.
     *
     * @param $path string The file path.
     */
    function makeSource($path)
    {
        $filename = File::nameWithoutExt($path);

        $driverAlias = $this->container->get('drivers')->getFromFile($path);

        return $this->container->make('source', [$path, $driverAlias, $filename]);
    }

    /**
     * Matches and makes ISource instances for each supported
     * file contained in $folder based or not in the $ext or $driverAlias.
     *
     * @param $folder string
     *    A path to a collection of source files.
     *
     * @param $ext string
     *    Extension for source files.
     *
     *    Setting $ext, will filter and load only files that
     *    matches this extension.
     *
     * @param $driverAlias string
     *    Driver alias
     *
     *    Setting the $driverAlias will filter and load only files
     *    supported by the driver associated with this $driverAlias.
     *
     * @return ISource[]
     */
    function makeSourceFromFolder($folder, $ext, $driverAlias)
    {
        $matchPattern = $this->genMatchPattern($ext);

        $files =  $this->matchFilesInFolder($folder, $matchPattern);

        return $this->makeSources($files, $driverAlias);
    }

    /**
     * Generates the match pattern used to filter
     * files in a directory based on their names
     *
     * @param string $ext
     *
     * @return string
     */
    function genMatchPattern($ext = null)
    {
        return '*.' . ($ext ?? '*');
    }

    /**
     * Matches all file names in $folder
     * that matches the $matchPattern
     *
     * @param $folder
     * @param $matchPattern
     *
     * @return array Contains the matched files
     */
    function matchFilesInFolder($folder, $matchPattern)
    {
        return $this->glob($folder, $matchPattern);
    }

    /**
     * Searches for files in $files that
     * has a driver that supports its extension
     * and makes an ISource instance for each file.
     *
     * @param $files array
     * @param $driverAlias string
     *
     * @return ISource[] Supported files
     */
    function makeSources(array $files, $driverAlias)
    {
        $fileSources = [];
        $drivers = $this->container->get('drivers');

        foreach ($files as $file) {
            if (is_null($driverAlias)) {
                if ($drivers->hasDriverThatSupportsExt($ext))
                    $fileSources[] = $this->makeSource($file);
            } else {
                if ($drivers->driverHasExt($driver, $ext)) {
                    $fileSources[] = $this->makeSource($file);
                }
            }
        }

        return $fileSources;
    }

    /**
     * Glob that is safe with streams (vfs for example)
     *
     * @param string $dir
     * @param string $matchPattern
     *
     * @return array
     *
     * @see https://github.com/mikey179/vfsStream/issues/2#issuecomment-252271019
     */
    function glob($dir, $matchPattern)
    {
        $files = scandir($dir);
        $found = [];

        foreach ($files as $filename) {
            if (in_array($filename, ['.', '..'])) {
                continue;
            }

            if (fnmatch($matchPattern, $filename)) {
                $found[] = $dir . DIRECTORY_SEPARATOR . $filename;
            }
        }

        return $found;
    }
}
