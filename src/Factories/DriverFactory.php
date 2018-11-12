<?php

namespace Unity\Component\Config\Factories;

use Unity\Component\Config\Contracts\Drivers\IDriver;
use Unity\Component\Config\Contracts\Factories\IDriverFactory;
use Unity\Component\Config\Drivers\IniDriver;
use Unity\Component\Config\Drivers\JsonDriver;
use Unity\Component\Config\Drivers\PhpDriver;
use Unity\Component\Config\Drivers\XmlDriver;
use Unity\Component\Config\Drivers\YamlDriver;
use Unity\Support\FileInfo;

/**
 * Class DriverFactory.
 *
 * Makes `IDriver` instances.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 *
 * @link   https://github.com/e200/
 */
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
        'xml'  => XmlDriver::class,
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
     * Makes an `IDrive` instance based on the given `$extension`.
     *
     * @param string $extension
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
