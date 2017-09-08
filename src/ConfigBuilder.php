<?php

namespace Unity\Component\Config;

class ConfigBuilder
{
    protected $src;
    protected $ext;
    protected $driverAlias;

    /**
     * Sets the source for configurations
     *
     * @param $src
     *
     * @return mixed
     */
    function setSource($src)
    {
        $this->src = $src;

        return $this;
    }

    /**
     * Sets the extension of the configuration files
     *
     * @param mixed $ext
     *
     * @return ConfigBuilder
     */
    function setExt($ext)
    {
        $this->ext = $ext;

        return $this;
    }

    /**
     * Sets the Driver to be used to get Configuration values
     *
     * @param string $driver
     *
     * @return ConfigBuilder
     */
    function setDriver($driver)
    {
        $this->driverAlias = $driver;

        return $this;
    }

    /**
     * @return bool
     */
    function hasDriver()
    {
        return !is_null($this->driverAlias);
    }

    /**
     * Builds and returns a new instance of Config class
     *
     * @return Config
     */
    function build()
    {
        return new Config(
            $this->src,
            $this->ext,
            $this->driverAlias
        );
    }
}
