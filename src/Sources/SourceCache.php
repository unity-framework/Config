<?php

namespace Unity\Component\Config\Sources;

use Unity\Contracts\Config\Sources\ISourceCache;

/**
 * Class SourceCache.
 * 
 * Cache manager for a source.
 * 
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 * @link   https://github.com/e200/
 */
class SourceCache implements ISourceCache
{
    /**
     * @var string Symbol that indicates whether a
     *             cache file should be cached forever
     *             or not.
     * */
    const CACHE_FOREVER_SYMBOL = '...';

    /** @var string Cache source */
    protected $source;

    /** @var string Where cache will be stored */
    protected $cachePath;

    /** @var string Expiration time in string, e.g: '2 hours', '2 seconds' */
    protected $cacheExpTime;

    public function __construct($source, $cachePath, $cacheExpTime)
    {
        $this->source = $source;
        $this->cachePath = $cachePath;
        $this->cacheExpTime = $cacheExpTime;
    }

    /**
     * Sets the cache data.
     *
     * @param array $data
     */
    public function set($data)
    {
        $expTimeWithSerializedData = $this->prependExpTime(serialize($data));

        $cacheName = $this->getCacheName();

        file_put_contents($cacheName, $expTimeWithSerializedData);
    }

    /**
     * Gets the cached data.
     *
     * @return mixed
     */
    public function get()
    {
        $cacheName = $this->getCacheName();

        $serializedData = '';

        $handler = fopen($cacheName, 'r');

        while (!feof($handler)) {
            /**
             * If `$firstRun` is set, that means we successfully
             * skipped the first line.
             */
            if (!isset($firstRun)) {
                fgets($handler);
            } else {
                $serializedData .= fgets($handler);
            }

            $firstRun = false;
        }

        fclose($handler);

        return unserialize($serializedData);
    }

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
    public function isHit()
    {
        $cacheName = $this->getCacheName();

        return file_exists($cacheName)
        &&
        !$this->isExpired()
        &&
        !$this->hasChangesOnSource();
    }

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
    public function isMiss()
    {
        $cacheName = $this->getCacheName();

        return !file_exists($cacheName)
        ||
        $this->isExpired()
        ||
        $this->hasChangesOnSource();
    }

    /**
     * Gets the source hash.
     *
     * @return string
     */
    protected function getSourceHash()
    {
        return md5($this->source);
    }

    /**
     * Returns the cache file name.
     *
     * @return string
     */
    protected function getCacheName()
    {
        return $this->cachePath.DIRECTORY_SEPARATOR.$this->getSourceHash($this->source);
    }

    /**
     * Checks if the cached data is expired.
     *
     * @return bool
     */
    protected function isExpired()
    {
        return $this->getSourceCacheExpTime() > time();
    }

    /**
     * Checks if the source was changed after
     * the last cache modification time.
     *
     * If the source was changed after the last cache modification
     * time, our current cached data is outdated.
     *
     * @return bool
     */
    protected function hasChangesOnSource()
    {
        return $this->sourceModTime() > $this->cacheModTime();
    }

    /**
     * Prepends the expiration data into the first line
     * of the serialized data that will be cached.
     *
     * @param string $data
     *
     * @return string
     */
    protected function prependExpTime($data)
    {
        $cacheExpTime = $this->cacheExpTime;

        if (is_null($cacheExpTime)) {
            $expTime = self::CACHE_FOREVER_SYMBOL;
        } else {
            $expTime = $this->convertoToTimestamp($this->cacheExpTime);            
        }

        return $expTime.PHP_EOL.$data;
    }

    /**
     * Gets the expiration time for this source cache.
     *
     * @return int|bool
     */
    protected function getSourceCacheExpTime()
    {
        $expTime = trim(fgets(fopen($this->getCacheName(), 'r+')));

        if (is_numeric($expTime)) {
            return (int) $expTime;
        } elseif($expTime == self::CACHE_FOREVER_SYMBOL) {
            return true;
        }

        return false;
    }

    /**
     * Converts an expiration time string to timestamp.
     *
     * @param $expTime Represents the expiration time as string.
     *                 e.g.: '1 hour', '2 days', '6 months'.
     *
     * @return int
     */
    protected function convertoToTimestamp($expTime)
    {
        return strtotime($expTime);
    }

    /**
     * Returns the last cache modification time.
     *
     * @return int
     */
    protected function cacheModTime()
    {
        return filemtime($this->cachePath);
    }

    /**
     * Returns the last source modification time.
     *
     * @return int
     */
    protected function sourceModTime()
    {
        return filemtime($this->source);
    }
}
