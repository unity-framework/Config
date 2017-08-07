<?php

namespace Unity\Component\Config\Drivers\File;

interface FileDriverInterface
{
    function resolve($file);
}