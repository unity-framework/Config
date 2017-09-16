<?php

namespace Unity\Component\Config\Drivers;

use Unity\Component\Config\Contracts\IDriver;
use Unity\Support\File;
use Unity\Component\Config\DriversRegistry;

class DriverFactory
{
    protected $driversRepository;

    function __construct(DriversRegistry $driversRepository)
    {
        $this->driversRepository = $driversRepository;
    }

    /**
     * Makes a new instance of the driver associated to the given alias
     *
     * @param $alias
     * @return mixed
     */
    function makeFromAlias($alias) : IDriver
    {
        $driver = $this->driversRepository->getFromAlias($alias);

        return new $driver;
    }

    /**
     * Makes a new instance of the driver that supports the given extension
     *
     * @param $ext
     *
     * @return string
     */
    function makeFromExt($ext) : IDriver
    {
        $driver = $this->driversRepository->getFromExt($ext);

        return new $driver;
    }

    /**
     * Makes a new instance of the driver that supports the given file extension
     *
     * @param $filename
     *
     * @return string
     */
    function makeFromFile($filename) : IDriver
    {
        $ext = File::ext($filename);

        return $this->makeFromExt($ext);
    }
}
