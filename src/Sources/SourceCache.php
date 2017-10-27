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
     * @param string $filename
     * 
     * @return string
     */
    protected function getHashedFileName($filename)
    {
        return md5($filename);
    }

    /**
     * Returns the cache file name.
     *
     * @return string
     */
    protected function getCacheFileName()
    {
        return $this->cachePath.DIRECTORY_SEPARATOR.$this->getHashedFileName($this->source);
    }

    /**
     * Sets the cache data.
     *
     * @param $data
     */
    public function set($data)
    {
        $serializedData = serialize($data);

        $dataWithPrependedExpTime = $this->prependExpTime($this->cacheExpTime, $serializedData);
        
        $cacheFileName = $this->getCacheFileName();
        
        file_put_contents($cacheFileName, $dataWithPrependedExpTime);
    }

    /**
     * Gets the cached data.
     *
     * @return mixed
     */
    public function get()
    {
        $cacheFileName = $this->getCacheFileName();

        $serializedData = '';

        $handler = fopen($cacheFileName, 'r');        

        while (!feof($handler)) {
            /**
             * If $firstRun is set, that means we successfully
             * skiped the first line.
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
     * @return bool
     */
    public function isHit()
    {
        $cacheFileName = $this->getCacheFileName();

        return $this->getExpTime($cacheFileName) > time();
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

    /**
     * Prepends the expiration data into the first line
     * of the data that will be cached.
     *
     * @param string $data
     * 
     * @return string
     */
    protected function prependExpTime($expTime, $data)
    {
        return $this->getExpTimeInTimestamp($expTime).PHP_EOL.$data;
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
     * Gets the expiration time in timestamp from a string.
     *
     * @param $expTime Represents the expiration time as string.
     *                 e.g.: '1 hour', '2 days', '6 months'.
     * 
     * @return int
     */
    protected function getExpTimeInTimestamp($expTime)
    {
        return strtotime($expTime);
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
