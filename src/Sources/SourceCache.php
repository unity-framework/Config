<?php

namespace Unity\Component\Config\Sources;

class SourceCache
{
    /**
     * Checks if at least one source was changed after
     * the last cache time.
     *
     * If any source was changed after the last cache time,
     * our current cached data is outdated.
     *
     * @return bool
     */
    function hasChanges()
    {
        if (filemtime($this->folder) > $this->cache->time($this->folder)) {
            return true;
        }

        return false;
    }
}