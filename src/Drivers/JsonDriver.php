<?php

namespace Unity\Component\Config\Drivers;

use Unity\Contracts\Config\Drivers\IDriver;

class JsonDriver implements IDriver
{
    /**
     * Returns the configuration as an array.
     *
     * @param $jsonfile
     *
     * @return array
     */
    public function parse($jsonfile) : array
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
