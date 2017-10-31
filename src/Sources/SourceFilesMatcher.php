<?php

namespace Unity\Component\Config\Sources;

use Unity\Component\Config\Exceptions\DriverNotFoundException;
use Unity\Component\Config\Exceptions\UnsupportedExtensionException;
use Unity\Contracts\Config\Drivers\IDriver;
use Unity\Contracts\Config\Factories\IDriverFactory;
use Unity\Contracts\Config\Factories\ISourceFactory;
use Unity\Contracts\Config\Sources\ISourceFile;
use Unity\Contracts\Config\Sources\ISourceFilesMatcher;

/**
 * Class FileSourcesRepository.
 *
 * Matches files in a source folder.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 *
 * @link   https://github.com/e200/
 */
class SourceFilesMatcher implements ISourceFilesMatcher
{
    protected $driverFactory;
    protected $sourceFactory;

    public function __construct(IDriverFactory $driverFactory, ISourceFactory $sourceFactory)
    {
        $this->driverFactory = $driverFactory;
        $this->sourceFactory = $sourceFactory;
    }

    /**
     * Matches source files in `$folder`.
     *
     * @param string $folder    Folder containing source files.
     * @param string $driver    Driver alias
     * @param string $extension Extension for source files.
     *                          Setting `$extension`, will filter and load only files that
     *                          matches this extension.
     *
     *                          Setting the `$driver` will filter and load only files
     *                          supported by the driver associated with this `$driver`.
     *
     * @throws UnreadableFolderException
     *
     * @return ISourceFile[]
     */
    public function match($folder, $driver, $extension)
    {
        $driver = $this->tryResolveDriver($driver, $extension);

        $filterPattern = $this->getFilterPattern($extension);

        $files = $this->filterFiles($folder, $filterPattern);

        return $this->getSourceFiles($files, $driver);
    }

    /**
     * Tries resolve the driver using `$driver` and `$extension` arguments .
     *
     * @param string $driver    A driver alias
     * @param string $extension Source files extension
     *
     * @throws UnsupportedExtensionException
     *
     * @return IDriver
     */
    protected function tryResolveDriver($driver, $extension)
    {
        if (!is_null($driver)) {
            $driver = $this->driverFactory->makeFromAlias($driver);

            /*
             * If there's no driver associated to this alias
             * we should throw this exception and stop here.
             */
            if ($driver === false) {
                throw new DriverNotFoundException("Cannot find driver \"{$driver}\".");
            }
        } elseif (!is_null($extension)) {
            $driver = $this->driverFactory->makeFromExt($extension);

            /*
             * If there's no driver that supports the given
             * extension we should throw this exception and
             * stop here.
             */
            if ($driver === false) {
                throw new UnsupportedExtensionException("Cannot find a driver that support \"{$extension}\" extension.");
            }
        }

        return $driver;
    }

    /**
     * Generates the filter pattern that will be used
     * to filter files in the folder based on their
     * extensions.
     *
     * @param string $extension
     *
     * @return string
     */
    protected function getFilterPattern($extension = null)
    {
        if (is_null($extension)) {
            return '*';
        } else {
            return '*.'.$extension;
        }
    }

    /**
     * Filter all files in `$folder` that matches the `$filterPattern`.
     *
     * Original description: Glob that is safe with streams (vfs for example).
     *
     * @param string $folder
     * @param string $filterPattern
     *
     * @return string[]
     *
     * @see https://github.com/mikey179/vfsStream/issues/2#issuecomment-252271019
     */
    protected function filterFiles($folder, $filterPattern)
    {
        $found = [];
        $files = scandir($folder);

        foreach ($files as $filename) {
            if (in_array($filename, ['.', '..'])) {
                continue;
            }

            if (fnmatch($filterPattern, $filename)) {
                $filepath = $folder.DIRECTORY_SEPARATOR.$filename;

                if (is_readable($filepath)) {
                    $found[] = $filepath;
                }
            }
        }

        return $found;
    }

    /**
     * Gets all supported `$sourceFiles`.
     *
     * @param string[] $sourceFiles
     * @param string   $driver
     *
     * @return ISourceFile[]
     */
    protected function getSourceFiles(array $sourceFiles, $driver)
    {
        $matchedSourceFiles = [];

        foreach ($sourceFiles as $sourceFile) {
            $matchedSource = $this->sourceFactory->makeFromFile($sourceFile, $driver);

            // If source is supported, add it.
            if ($matchedSource !== false) {
                $matchedSourceFiles[] = $matchedSource;
            }
        }

        // Our sources! :)
        return $matchedSourceFiles;
    }
}
