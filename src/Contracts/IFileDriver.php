<?php

namespace Unity\Component\Config\Contracts;

interface IFileDriver extends IDriver
{
    /**
     * Parses $file content and returns its data
     *
     * @param $file
     *
     * @return array
     */
    function parse($file) : array;
}