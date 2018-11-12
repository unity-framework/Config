<?php

namespace Unity\Component\Config\Contracts\Factories;

interface IDriverFactory
{
    /**
     * Gets the driver that `$alias` represents.
     *
     * @param $alias
     *
     * @return string
     */
    public function get($alias);

    /**
     * Checks if a driver within the given `$alias` exists.
     *
     * @param $alias
     *
     * @return bool
     */
    public function has($alias);

    /**
     * Returns all available drivers.
     *
     * @return array
     */
    public function getAll();

    /**
     * Makes an IDriver instance based on the given `$extension`.
     *
     * @param $extension string
     *
     * @return IDriver|false
     */
    public function makeFromExt($extension);

    /**
     * Makes an IDriver instance based on the given `$file` extension.
     *
     * @param $file string
     *
     * @return IDriver|false
     */
    public function makeFromFile($file);

    /**
     * Makes an IDriver instance based on the given `$alias`.
     *
     * @param $alias string
     *
     * @return IDriver|false
     */
    public function makeFromAlias($alias);
}
