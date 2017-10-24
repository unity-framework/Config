<?php

namespace Unity\Component\Config\Sources;

use Unity\Contracts\Config\Sources\IFileSource;
use Unity\Contracts\Config\Sources\ISource;
use Unity\Contracts\Config\Sources\ISourceFilesMatcher;

/**
 * Class FolderSource.
 *
 * Represents a folder source.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 */
class FolderSource implements ISource
{
    protected $folder;
    protected $driver;
    protected $ext;

    protected $sourceFilesMatcher;
    protected $cache;

    function __construct(
        $folder,
        $driver,
        $ext,
        ISourceFilesMatcher $sourceFilesMatcher
        ) {
        $this->folder = $folder;
        $this->driver = $driver;
        $this->ext = $ext;

        $this->sourceFilesMatcher = $sourceFilesMatcher;
    }

    /**
     * @return string
     */
    function getSource()
    {
        return $this->folder;
    }

    /**
     * Gets all available configurations data.
     *
     * @return array
     */
    function getData()
    {
        $data = [];

        foreach ($this->getSourceFiles() as $sourceFile) {
            $data[$sourceFile->getKey()] = $sourceFile->getData();
        }

        return $data;
    }

    /**
     * Returns an array containing all source files
     * founded on `$this->folder`.
     * 
     * @return IFileSource[]
     */
    protected function getSourceFiles()
    {
        return $this->sourceFilesMatcher->match(
            $this->folder,
            $this->driver,
            $this->ext
        );
    }
}
