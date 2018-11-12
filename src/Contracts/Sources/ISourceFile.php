<?php

namespace Unity\Component\Config\Contracts\Sources;

/**
 * Interface ISourceFile.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 */
interface ISourceFile extends ISource
{
    /**
     * @return string
     */
    public function getKey();
}
