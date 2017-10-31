<?php

namespace Unity\Component\Config\Drivers;

use Unity\Contracts\Config\Drivers\IDriver;

/**
 * Class PhpDriver.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 * @link   https://github.com/e200/
 */
class PhpDriver implements IDriver
{
    /**
     * Loads and returns the configs array.
     *
     * @param $phpfile
     *
     * @return array
     */
    public function load($phpfile) : array
    {
        $return = require $phpfile;

        if (is_callable($return)) {
            return call_user_func($return);
        } else {
            return $return;
        }
    }

    /**
     * Returns supported extensions.
     *
     * @return array
     */
    public function extensions() : array
    {
        return ['php', 'inc'];
    }
}
