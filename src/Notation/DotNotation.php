<?php

namespace Unity\Component\Config\Notation;

class DotNotation implements INotation
{
    /**
     * Denotes a string using dot (.) as separator
     *
     * @param $notation
     * @return INotationBag
     */
    static function denote($notation)
    {
        $exp = explode('.', $notation);

        $root = $exp[0];
        $keys = [];

        /**
         * Unsetting the root element,
         * we keep only the keys
         */
        unset($exp[0]);

        foreach ($exp as $key)
            $keys[] = $key;

        return new NotationBag($root, $keys);
    }
}