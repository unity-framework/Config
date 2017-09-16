<?php

namespace Unity\Component\Config\Contracts;

/**
 * Interface ISourceMatcher
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 */
interface ISource
{
    /**
     * @return array
     */
    function getData();

    /**
     * @return string
     */
    function getKey();

    /**
     * @return bool
     */
    function hasKey();

    /**
     * @return mixed
     */
    function getSource();

    /**
     * @return string
     */
    function getDriverAlias();
}
