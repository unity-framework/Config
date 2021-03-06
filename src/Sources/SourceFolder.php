<?php

namespace Unity\Component\Config\Sources;

use Unity\Component\Config\Contracts\Sources\ISource;
use Unity\Component\Config\Contracts\Sources\ISourceFile;
use Unity\Component\Config\Contracts\Sources\ISourceFilesMatcher;

/**
 * Class SourceFolder.
 *
 * Represents a folder source.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 *
 * @link   https://github.com/e200/
 */
class SourceFolder implements ISource
{
    protected $folder;
    protected $driver;
    protected $ext;

    protected $sourceFilesMatcher;

    public function __construct(
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
    public function getSource()
    {
        return $this->folder;
    }

    /**
     * Gets all available configs data.
     *
     * @return array
     */
    public function getData()
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
     * @return ISourceFile[]
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
