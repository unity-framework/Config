<?php

/*
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 */

namespace Unity\Component\Config\Contracts;

use Unity\Component\Config\Notation\NotationBag;

interface INotation
{
    /**
     * Denotes a string.
     *
     * @param $notation
     *
     * @return NotationBag
     */
    public static function denote($notation);
}
