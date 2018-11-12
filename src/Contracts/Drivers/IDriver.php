<?php

namespace Unity\Component\Config\Contracts\Drivers;

/**
 * Interface IDriver.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 */
interface IDriver
{
    /**
     * Loads configurations from `$source`.
     *
     * @param $source string Configurations source
     *
     * @return array
     */
    public function Load($source) : array;

    /**
     * Returns supported extensions.
     *
     * @return array
     */
    public function extensions() : array;
}
