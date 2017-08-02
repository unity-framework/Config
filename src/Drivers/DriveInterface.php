<?php

namespace Unity\Component\Configurable\Drivers;

interface DriveInterface
{
    function get($config, $source);
}