<?php

namespace Unity\Component\Config\Factories;

use Unity\Contracts\Config\Factories\IDriverFactory;
use Unity\Contracts\Config\Factories\ISourceFactory;
use Unity\Contracts\Config\Sources\ISource;
use Unity\Contracts\Container\IContainer;
use Unity\Support\FileInfo;

class SourceFactory implements ISourceFactory
{
    /** @var IContainer */
    protected $container;

    /** @var IDriverFactory */
    protected $driverFactory;

    /** @var FileInfo */
    protected $fileInfo;

    public function __construct(
        IDriverFactory $driverFactory,
        IContainer $container,
        FileInfo $fileInfo
        ) {
        $this->driverFactory = $driverFactory;
        $this->container = $container;
        $this->fileInfo = $fileInfo;
    }

    /**
     * Makes and returns an ISource instance that represents a file.
     *
     * @param string $file   The source.
     * @param string $driver The driver that will be used.
     * @param string $ext
     *
     * @return ISource|bool
     */
    public function makeFromFile($file, $driver = null, $ext = null)
    {
        if (!is_object($driver)) {
            if (!is_null($driver)) {
                $driver = $this->driverFactory->makeFromAlias($driver);
            } elseif (!is_null($ext)) {
                $driver = $this->driverFactory->makeFromExt($ext);
            } else {
                $driver = $this->driverFactory->makeFromFile($file);
            }
        }

        /*
         * If `$driver` isn't false, that means we got our driver.
         *
         * We can make a new ISource instance.
         */
        if ($driver) {
            $filename = $this->fileInfo->name($file);

            return $this->container->make('fileSource', [$filename, $file, $driver]);
        }

        // There's no driver that supports `$file`, unfortunately.
        return false;
    }

    /**
     * Makes and returns an ISource instance that represents a folder.
     *
     * @param string $folder
     * @param string $driver The driver that will be used.
     * @param string $ext
     *
     * @return ISource
     */
    public function makeFromFolder($folder, $driver = null, $ext = null)
    {
        return $this->container->make('folderSource', [$folder, $driver, $ext]);
    }
}
