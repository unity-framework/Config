<?php

namespace Unity\Component\Config\Drivers;

use Unity\Component\Config\Contracts\Drivers\IDriver;

/**
 * Class JsonDriver.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 *
 * @link   https://github.com/e200/
 */
class JsonDriver implements IDriver
{
    /**
     * Loads and returns the configs array.
     *
     * @param $jsonfile
     *
     * @return array
     */
    public function load($jsonfile) : array
    {
        $file_content = file_get_contents($jsonfile);

        return (array) json_decode($file_content);
    }

    /**
     * Returns supported extensions.
     *
     * @return array
     */
    public function extensions() : array
    {
        return ['json'];
    }
}
