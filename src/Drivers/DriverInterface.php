<?php

namespace Unity\Component\Configurable\Drivers;

interface DriverInterface
{
    function get($config, $source);
}