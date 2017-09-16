<?php

namespace Unity\Component\Config\Contracts;

/**
 * Interface ISourceMatcher.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 */
interface ISourceMatcher
{
    public function match($src, $ext, $driver);
}
