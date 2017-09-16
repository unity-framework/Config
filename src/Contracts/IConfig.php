<?php

namespace Unity\Component\Config\Contracts;

/**
 * Interface IConfig.
 *
 * @author: Eleandro Duzentos <e200|eleandro@inbox.ru>
 */
interface IConfig
{
    /**
     * Returns the configuration value.
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
}
