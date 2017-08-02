<?php

namespace Unity\Component\Configurable\Drivers;

class ArrayFile implements DriveInterface
{
    function get($config, $source)
    {
        return [];
    }
}