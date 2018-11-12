<?php

namespace Unity\Component\Config\Contracts;

/**
 * Interface ILoader.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 */
interface ILoader
{
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
    public function load($source, $driver, $ext);
}
