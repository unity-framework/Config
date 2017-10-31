<?php

namespace Unity\Component\Config;

use Unity\Component\Config\Exceptions\DriverNotFoundException;
use Unity\Component\Config\Exceptions\UnreadableSourceException;

/**
 * Class Loader.
 *
 * Loads all available and supported config sources.
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
        if (!is_readable($source)) {
            throw new UnreadableSourceException('Permission denied while trying to read source.');
        }

        if (is_file($source)) {
            $sourceInstance = $this->sourceFactory->makeFromFile($source, $driver, $ext);

            if ($sourceInstance === false) {
                throw new DriverNotFoundException('Cannot find any driver that supports the given source.');
            }
        } elseif (is_dir($source)) {
            $sourceInstance = $this->sourceFactory->makeFromFolder($source, $driver, $ext);
        }

        return $sourceInstance->getData();
    }
}
