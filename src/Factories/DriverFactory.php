<?php

namespace Unity\Component\Config\Factories;

use Unity\Component\Config\Drivers\IniDriver;
use Unity\Component\Config\Drivers\JsonDriver;
use Unity\Component\Config\Drivers\PhpDriver;
use Unity\Component\Config\Drivers\YamlDriver;
use Unity\Contracts\Config\Factories\IDriverFactory;
use Unity\Contracts\Config\IDriver;
use Unity\Support\FileInfo;

class DriverFactory implements IDriverFactory
{
    /** @var FileInfo */
    protected $fileInfo;

    public function __construct(FileInfo $fileInfo)
    {
        $this->fileInfo = $fileInfo;
    }

    /** Available drivers, their aliases and extensions */
    protected $drivers = [
        'php'  => PhpDriver::class,
        'ini'  => IniDriver::class,
        'json' => JsonDriver::class,
        'yml'  => YamlDriver::class,
    ];

    /**
     * Gets the driver that `$alias` represents.
     *
     * @param $alias
     *
     * @return string
     */
    public function get($alias)
    {
        return $this->drivers[$alias];
    }

    /**
     * Checks if a driver within the given `$alias` exists.
     *
     * @param $alias
     *
     * @return bool
     */
    public function has($alias)
    {
        return array_key_exists($alias, $this->drivers);
    }

    /**
     * Returns all available drivers.
     *
     * @return array
     */
    public function getAll()
    {
        return $this->drivers;
    }

    /**
     * Makes an IDriver instance based on the given `$extension`.
     *
     * @param $extension string
     *
     * @return IDriver|false
     */
    public function makeFromExt($extension)
    {
        $drivers = $this->getAll();

        foreach ($drivers as $driver) {
            $driver = new $driver();

            if (in_array($extension, $driver->extensions())) {
                return $driver;
            }

            unset($driver);
        }

        return false;
    }

    /**
     * Makes an IDriver instance based on the given `$file` extension.
     *
     * @param $file string
     *
     * @return IDriver|false
     */
    public function makeFromFile($file)
    {
        $ext = $this->fileInfo->ext($file);

        return $this->makeFromExt($ext);
    }

    /**
     * Makes an IDriver instance based on the given `$alias`.
     *
     * @param $alias string
     *
     * @return IDriver|false
     */
    public function makeFromAlias($alias)
    {
        if ($this->has($alias)) {
            $driver = $this->get($alias);

            return new $driver();
        }

        return false;
    }
}
