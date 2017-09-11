<?php

/*
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 */

namespace Unity\Component\Config\Drivers;

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
    public function makeFromAlias($alias)
    {
        $driver = $this->driversRepository->getFromAlias($alias);

        return new $driver();
    }

    /**
     * Makes a new instance of the driver that supports the given extension.
     *
     * @param $ext
     *
     * @return mixed
     */
    public function makeFromExt($ext)
    {
        $driver = $this->driversRepository->getFromExt($ext);

        return new $driver();
    }

    public function makeFromFile($filename)
    {
        $ext = File::ext($filename);

        return $this->makeFromExt($ext);
    }
}
