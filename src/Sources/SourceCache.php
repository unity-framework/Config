<?php

namespace Unity\Component\Config\Sources;

class SourceCache
{
    /** @var string Cache source */
    protected $source;

    /** @var string Where cache will be stored */
    protected $cachePath;

    protected $cacheExpTime;

    public function __construct($source, $cachePath, $cacheExpTime)
    {
        $this->source = $source;
        $this->cachePath = $cachePath;
        $this->cacheExpTime = $cacheExpTime;
    }

    /**
     * Gets the hash for the source.
     *
     * @return string
     */
    protected function getHash()
    {
        return md5($this->source);
    }
    
    /**
     * Returns the cache file name.
     *
     * @return string
     */
    protected function getCacheFileName()
    {
        return $this->cachePath . DIRECTORY_SEPARATOR . $this->getHash();
    }

    /**
     * Gets the cached data.
     *
     * @return mixed
     */
    public function get()
    {
        return unserialize(file_get_contents($this->getCacheFileName()));
    }

    /**
     * Sets the cache data.
     *
     * @param $data
     */
    public function set($data)
    {
        $serializedData = serialize($data);

        //$data = $this->prependExpTime();

        file_put_contents($this->getCacheFileName(), $serializedData);
    }

    /**
     * Checks if the cached data is hit.
     *
     * @return boolean
     */
    public function isHit()
    {
        return $this->getExpTime($this->getCacheFileName()) > time();
    }

    /**
     * Checks if at least one source was changed after
     * the last cache time.
     *
     * If any source was changed after the last cache time,
     * our current cached data is outdated.
     *
     * @return bool
     */
    public function hasChanges()
    {
        return $this->lastSourceModTime() > $this->lastCacheTime();
    }

    protected function prependExpTime($data)
    {        
        return $this->getExpirationTimestamp() . "\n" . $data;
    }

    /**
     * Gets the expiration time for this source cache.
     *
     * @param $file Name of the file in the cache.
     * 
     * @return int|false
     */
    protected function getExpTime($file)
    {
        $expTime = trim(fgets(fopen($file, 'r+')));
                
        return is_numeric($expTime) ? (int) $expTime : false;
    }
    
    /**
     * Gets the expiration time timestamp from the
     * cache expiration string.
     *
     * @return int
     */
    protected function getExpirationTimestamp()
    {
        return strtotime($this->cacheExpTime);
    }

    /**
     * Returns the last cache time.
     *
     * @return int
     */
    protected function lastCacheTime()
    {
        return filemtime($this->cachePath);
    }

    /**
     * Returns the last source modification time.
     *
     * @return int
     */
    protected function lastSourceModTime()
    {
        return filemtime($this->source);
    }
}
