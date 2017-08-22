<?php

namespace Unity\Component\Config\Notation;

interface INotationBag
{
    /**
     * @return string
     */
    function getRoot();

    /**
     * @return array
     */
    function getKeys();
}