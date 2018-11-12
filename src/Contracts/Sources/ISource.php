<?php

namespace Unity\Component\Config\Contracts\Sources;

/**
 * Interface ISource.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 */
interface ISource
{
    /**
     * @return array
     */
    public function getData();

    /**
     * @return mixed
     */
    public function getSource();
}
