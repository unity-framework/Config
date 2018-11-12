<?php

namespace Unity\Component\Config\Contracts\Sources;

/**
 * Interface ISourceCache.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 */
interface ISourceCache
{
    /**
     * Sets the cache data.
     *
     * @param $data
     */
    public function set($data);

    /**
     * Gets the cached data.
     *
     * @return mixed
     */
    public function get();

    /**
     * Checks if the cached data is hit.
     *
     * The cached data is hit if:
     *
     * - The cached data is present.
     *
     * - The cached data does'nt rish
     *   the expiration time.
     *
     * - The source was not modified
     *   since the last cache.
     *
     * @return bool
     */
    public function isHit();

    /**
     * Checks if the cached data is miss.
     *
     * The cached data is miss if:
     *
     * - The cached data isn't present.
     *
     * - The cached data rished
     *   the expiration time.
     *
     * - The source was modified
     *   since the last cache.
     *
     * @return bool
     */
    public function isMiss();
}
