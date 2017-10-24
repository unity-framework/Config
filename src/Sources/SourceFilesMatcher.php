<?php

namespace Unity\Component\Config\Sources;

use Unity\Component\Config\Exceptions\UnreadableFolderException;
use Unity\Contracts\Config\Drivers\IDriver;
use Unity\Contracts\Config\Factories\IDriverFactory;
use Unity\Contracts\Config\Factories\ISourceFactory;
use Unity\Component\Config\Exceptions\UnsupportedExtensionException;
use Unity\Contracts\Config\Sources\IFileSource;
use Unity\Contracts\Config\Sources\ISourceFilesMatcher;

/**
 * Class FileSourcesRepository.
 *
 * Matches files in a source folder.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 */
class SourceFilesMatcher implements ISourceFilesMatcher
{
    protected $driverFactory;
    protected $sourceFactory;

    function __construct(IDriverFactory $driverFactory, ISourceFactory $sourceFactory)
    {
        $this->driverFactory = $driverFactory;
        $this->sourceFactory = $sourceFactory;
    }

    /**
     * Matchs source files in `$folder`.
     *
     * @param string $folder Folder containing source files.
     *
     * @param string $driver Driver alias
     *
     * @param string $ext Extension for source files.
     *                    Setting `$ext`, will filter and load only files that
     *                    matches this extension.
     *
     *                    Setting the `$driver` will filter and load only files
     *                    supported by the driver associated with this `$driver`.
     *
     * @return IFileSource[]
     *
     * @throws UnreadableFolderException
     */
    function match($folder, $driver, $ext)
    {
        if (is_null($driver) && !($ext)) {
            $driver = $this->tryGetDriverUsingExt($ext);
        }

        if (is_readable($folder)) {
            return $this->matchSources($folder, $driver, $ext);
        } else {
            throw new UnreadableFolderException("Unreadable source folder.");
        }
    }

    /**
     * Gets an IDriver instance that supports `$extension`,
     * if `$extension` isn't null, otherwise does nothing.
     *
     * @param $extension
     *
     * @return null|IDriver
     *
     * @throws UnsupportedExtensionException
     */
    protected function tryGetDriverUsingExt($extension)
    {
        if (!is_null($extension)) {
            $driver = $this->driverFactory->makeFromExt($extension);
            
            if ($driver === false) {
                throw new UnsupportedExtensionException("Cannot find a driver that support \"{$extension}\" extension.");
            }

            return $driver;
        }
    }

    /**
     * Matches source files in `$folder`.
     *
     * @param string $folder Folder containing source files.
     *
     * @param string $driver Driver alias
     *
     * @param string $ext Extension for source files.
     *                    Setting `$ext`, will filter and load only files that
     *                    matches this extension.
     *
     *                    Setting the `$driver` will filter and load only files
     *                    supported by the driver associated with this `$driver`.
     *
     * @return false|IFileSource[]
     */
    protected function matchSources($folder, $driver, $ext)
    {
        $filterPattern = $this->getFilterPattern($ext);

        $files = $this->filterFiles($folder, $filterPattern);

        return $this->getSourceFiles($files, $driver);
    }

    /**
     * Generates the filter pattern that will be used
     * to filter files in the folder based on their
     * extensions.
     *
     * @param string $ext
     *
     * @return string
     */
    protected function getFilterPattern($ext = null)
    {
        return '*.' . ($ext ?? '*');
    }

    /**
     * Filter all files in `$folder` that matches the `$filterPattern`.
     *
     * @param string $folder
     * @param string $filterPattern
     *
     * @return array Contains matched files.
     */
    protected function filterFiles($folder, $filterPattern)
    {
        return $this->glob($folder, $filterPattern);
    }

    /**
     * Gets all supported `$sourceFiles`.
     *
     * @param string[] $sourceFiles
     * @param string   $driver
     * 
     * @return IFileSource[]|false
     */
    protected function getSourceFiles(array $sourceFiles, $driver)
    {
        $matchedSourceFiles = [];

        foreach ($sourceFiles as $sourceFile) {
            $matchedSource = $this->sourceFactory->makeFromFile($sourceFile, $driver);

            if ($matchedSource !== false) {
                $matchedSourceFiles[] = $matchedSource;
            }
        }

        return $matchedSourceFiles;
    }

    /**
     * Glob that is safe with streams (vfs for example)
     *
     * @param string $dir
     * @param string $filterPattern
     *
     * @return string[]
     *
     * @see https://github.com/mikey179/vfsStream/issues/2#issuecomment-252271019
     */
    protected function glob($dir, $filterPattern)
    {
        $found = [];
        $files = scandir($dir);

        foreach ($files as $filename) {
            if (in_array($filename, ['.', '..'])) {
                continue;
            }

            if (fnmatch($filterPattern, $filename)) {
                $found[] = $dir . DIRECTORY_SEPARATOR . $filename;
            }
        }

        return $found;
    }
}
