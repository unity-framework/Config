<?php

namespace Unity\Component\Config\Contracts;

/**
 * Interface ISourceMatcher.
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
     * @return string
     */
    public function getKey();

    /**
     * @return bool
     */
    public function hasKey();

    /**
     * @return mixed
     */
    public function getSource();

    /**
     * @return string
     */
    public function getDriverAlias();
}
