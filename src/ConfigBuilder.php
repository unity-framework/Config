<?php

/*
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 */

namespace Unity\Component\Config;

class ConfigBuilder
{
    protected $src;
    protected $ext;
    protected $driverAlias;

    /**
     * Sets the source for configurations.
     *
     * @param $src
     *
     * @return mixed
     */
    public function setSource($src)
    {
        $this->src = $src;

        return $this;
    }

    /**
     * Sets the extension of the configuration files.
     *
     * @param mixed $ext
     *
     * @return ConfigBuilder
     */
    public function setExt($ext)
    {
        $this->ext = $ext;

        return $this;
    }

    /**
     * Sets the Driver to be used to get Configuration values.
     *
     * @param string $driver
     *
     * @return ConfigBuilder
     */
    public function setDriver($driver)
    {
        $this->driverAlias = $driver;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasDriver()
    {
        return !is_null($this->driverAlias);
    }

    /**
     * Builds and returns a new instance of Config class.
     *
     * @return Config
     */
    public function build()
    {
        return new Config(
            $this->src,
            $this->ext,
            $this->driverAlias
        );
    }
}
