<?php

namespace Unity\Component\Config\Factories;

use Unity\Contracts\Config\Drivers\IDriver;
use Unity\Contracts\Config\Factories\IDriverFactory;
use Unity\Contracts\Config\Factories\ISourceFactory;
use Unity\Contracts\Config\Sources\ISource;
use Unity\Contracts\Config\Sources\ISourceFile;
use Unity\Contracts\Container\IContainer;
use Unity\Support\FileInfo;

/**
 * Class DriverFactory.
 *
 * Makes `ISource` instances.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 *
 * @link   https://github.com/e200/
 */
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
     * Resolves the necessary driver to make
     * the `IFileSource` instance.
     *
     * @param string $file   The source.
     * @param string $driver The driver that will be used.
     * @param string $ext
     *
     * @return IDriver|bool
     */
    protected function resolveDriver($file, $driver, $ext)
    {
        if (!is_null($driver)) {
            return $this->driverFactory->makeFromAlias($driver);
        } elseif (!is_null($ext)) {
            return $this->driverFactory->makeFromExt($ext);
        } else {
            return $this->driverFactory->makeFromFile($file);
        }
    }

    /**
     * Makes and returns an ISource instance that represents a file.
     *
     * @param string $file   The source.
     * @param string $driver The driver that will be used.
     * @param string $ext
     *
     * @return ISourceFile|bool
     */
    public function makeFromFile($file, $driver = null, $ext = null)
    {
        /**
         * If `$driver` is an object that means our driver was
         * already resolved outside of `$this` scope, so, we
         * don't need to resolve it again.
         */
        if (!is_object($driver)) {
            $driver = $this->resolveDriver($file, $driver, $ext);
        }

        /*
         * If `$driver` isn't false, that means we got our driver.
         *
         * We can make a new IFileSource instance.
         */
        if ($driver) {
            $filename = $this->fileInfo->name($file);

            return $this->container->make('sourceFile', [$filename, $file, $driver]);
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
        return $this->container->make('sourceFolder', [$folder, $driver, $ext]);
    }
}
