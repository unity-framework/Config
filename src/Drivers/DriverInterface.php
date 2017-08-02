<?php

namespace Unity\Component\Configuration\Drivers;

interface DriverInterface
{
    function get($config, $source);
}