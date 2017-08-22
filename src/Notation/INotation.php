<?php

namespace Unity\Component\Config\Notation;

interface INotation
{
    /**
     * Denotes a string
     *
     * @param $notation
     * @return INotationBag
     */
    static function denote($notation);
}