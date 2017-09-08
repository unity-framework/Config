<?php

namespace Unity\Component\Config\Drivers;

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
    function makeFromAlias($alias)
    {
        $driver = $this->driversRepository->getFromAlias($alias);

        return new $driver;
    }

    /**
     * Makes a new instance of the driver that supports the given extension
     *
     * @param $ext
     * @return mixed
     */
    function makeFromExt($ext)
    {
        $driver = $this->driversRepository->getFromExt($ext);

        return new $driver;
    }

    function makeFromFile($filename)
    {
        $ext = File::ext($filename);

        return $this->makeFromExt($ext);
    }
}
