<?php

namespace Unity\Component\Config\Notation;

use Unity\Component\Config\Contracts\INotation;

/**
 * Class DotNotation.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 */
class DotNotation implements INotation
{
    /**
     * Denotes a string using dot (.) as separator.
     *
     * @param $notation
     *
     * @return string[]
     */
    public static function denote($notation)
    {
        return explode('.', $notation);
    }
}
