<?php

namespace Unity\Component\Config;

use ArrayAccess;
use Countable;
use Unity\Support\Arr;
use Unity\Component\Config\Contracts\IConfig;
use Unity\Component\Config\Exceptions\ConfigNotFoundException;
use Unity\Component\Config\Notation\DotNotation;

/**
 * Class Config.
 *
 * Gets and checks configurations data.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 */
class Config implements IConfig, ArrayAccess, Countable
{
    protected $data;

    /**
     * @param $data array Contains configurations data
     */
    function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Gets a configuration value
     *
     * @param $config
     *
     * @return mixed
     */
    function get($config)
    {
        $keys = DotNotation::denote($config);

        return Arr::nestedGet($this->data, $keys);
    }

    /**
     * Checks ifs a configuration exists
     *
     * @param $config
     *
     * @return bool
     */
    function has($config)
    {
        $keys = DotNotation::denote($config);

        return Arr::nestedHas($this->data, $keys);
    }

    /**
     * Gets all available configurations
     *
     * @return array
     */
    function getAll()
    {
        return $this->data;
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        // TODO: Implement offsetExists() method.
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        // TODO: Implement offsetGet() method.
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        // TODO: Implement offsetSet() method.
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return count($this->data);
    }
}
