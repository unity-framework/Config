<?php

namespace Unity\Component\Config\Notation;

class DotNotation
{
    /**
     * Denotes a string
     *
     * @param $notation
     * @return mixed
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