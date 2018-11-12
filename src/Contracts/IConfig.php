<?php

namespace Unity\Component\Config\Contracts;

/**
 * Interface IConfig.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 */
interface IConfig
{
    /**
     * Gets a configuration value.
     *
     * @param $config
     *
     * @return mixed
     */
    public function get($config);

    /**
     * Checks if a configuration exists.
     *
     * @param $config
     *
     * @return bool
     */
    public function has($config);

    /**
     * Gets all available configurations.
     *
     * @return array
     */
    public function getAll();
}
