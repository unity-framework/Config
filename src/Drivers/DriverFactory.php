<?php

namespace Unity\Component\Config\Drivers;

use Unity\Component\Config\Contracts\IDriver;
use Unity\Component\Config\DriversRegistry;
use Unity\Support\File;

class DriverFactory
{
    protected $driversRepository;

    public function __construct(DriversRegistry $driversRepository)
    {
        $this->driversRepository = $driversRepository;
    }

    /**
     * Makes a new instance of the driver associated to the given alias.
     *
     * @param $alias
     *
     * @return mixed
     */
    public function makeFromAlias($alias) : IDriver
    {
        $driver = $this->driversRepository->getFromAlias($alias);

        return new $driver();
    }

    /**
     * Makes a new instance of the driver that supports the given extension.
     *
     * @param $ext
     *
     * @return string
     */
    public function makeFromExt($ext) : IDriver
    {
        $driver = $this->driversRepository->getFromExt($ext);

        return new $driver();
    }

    /**
     * Makes a new instance of the driver that supports the given file extension.
     *
     * @param $filename
     *
     * @return string
     */
    public function makeFromFile($filename) : IDriver
    {
        $ext = File::ext($filename);

        return $this->makeFromExt($ext);
    }
}
