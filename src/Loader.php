<?php

namespace Unity\Component\Config;

use Unity\Component\Config\Exceptions\DriverNotFoundException;
use Unity\Contracts\Config\Factories\ISourceFactory;
use Unity\Contracts\Config\ILoader;

/**
 * Class Loader.
 *
 * Loads all available and supported configuration sources.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 *
 * @link   https://github.com/e200/
 */
class Loader implements ILoader
{
    protected $sourceFactory;

    public function __construct(ISourceFactory $sourceFactory)
    {
        $this->sourceFactory = $sourceFactory;
    }

    /**
     * Loads a source and get their data.
     *
     * @param string $source
     * @param string $driver
     * @param string $ext
     *
     * @throws DriverNotFoundException
     *
     * @return mixed
     */
    public function load($source, $driver, $ext)
    {
        $sourceInstance = null;

        if (is_file($source)) {
            $sourceInstance = $this->sourceFactory->makeFromFile($source, $driver, $ext);
        } elseif (is_dir($source)) {
            $sourceInstance = $this->sourceFactory->makeFromFolder($source, $driver, $ext);
        }

        if ($sourceInstance === false) {
            throw new DriverNotFoundException('Cannot find any driver that supports the given source.');
        }

        return $sourceInstance->getData();
    }
}
