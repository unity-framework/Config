<?php

namespace Unity\Component\Config;

use ArrayAccess;
use Countable;
use Unity\Component\Config\Exceptions\RuntimeModificationException;
use Unity\Contracts\Config\IConfig;
use Unity\Contracts\Notator\INotator;

/**
 * Class Config.
 *
 * configs manager.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 *
 * @link   https://github.com/e200/
 */
class Config implements IConfig, ArrayAccess, Countable
{
    /** @var array */
    protected $data;

    /** @var bool */
    protected $allowModifications;

    /** @var INotator */
    protected $notator;

    /**
     * @param array $data               Contains configs data.
     * @param bool  $allowModifications Enable or disable read only mode.
     */
    public function __construct(array $data, $allowModifications, INotator $notator)
    {
        $this->data = $data;
        $this->allowModifications = $allowModifications;
        $this->notator = $notator;
    }

    /**
     * Sets a config value at runtime.
     *
     * @param string $config dot notation string that references the
     *                       config to be replaced.
     * @param mixed  $value  The new value.
     *
     * @throws RuntimeModificationException
     *
     * @return static
     */
    public function set($config, $value)
    {
        if (!$this->allowModifications()) {
            throw new RuntimeModificationException('Cannot modify configs in read only mode.');
        }

        $keys = $this->notator->denote($config);

        $this->innerSet($keys, $value);

        return $this;
    }

    /**
     * Gets a config value.
     *
     * @param string $config A dot notation string that references the
     *                       config to be getted.
     *
     * @return mixed
     */
    public function get($config)
    {
        $keys = $this->notator->denote($config);

        return $this->innerGet($keys);
    }

    /**
     * Checks ifs a config exists.
     *
     * @param string $config A dot notation string that references the
     *                       config to be checked.
     *
     * @return bool
     */
    public function has($config)
    {
        $keys = $this->notator->denote($config);

        return $this->innerHas($keys);
    }

    /**
     * Counts the number of configs.
     *
     * @return int
     */
    public function count()
    {
        return $this->recCount($this->data);
    }

    /**
     * Gets all available configs.
     *
     * @return array
     */
    public function getAll()
    {
        return $this->data;
    }

    /**
     * Sets a config using the `$offset`.
     *
     * @param mixed $offset Configuration offset.
     * @param mixed $value  The new value.
     *
     * @throws RuntimeModificationException
     */
    public function offsetSet($offset, $value)
    {
        if (!$this->allowModifications()) {
            throw new RuntimeModificationException('Cannot modify configs in read only mode.');
        }

        $this->data[$offset] = $value;
    }

    /**
     * Gets a config using the `$offset`.
     *
     * @param mixed $offset Configuration offset.
     *
     * @return mixed
     */
    public function &offsetGet($offset)
    {
        /**
         * We're returning configs
         * by reference, that can let
         * anyone modify configs
         * data.
         *
         * But if read only mode is enabled,
         * we can't let anyone modify configs
         * so, to prevent this, we'll return
         * a copy of `$this->data` and if someone
         * modify that returned copy, these changes
         * will not be reflected to `$this->data`.
         */
        if (!$this->allowModifications()) {
            $copy = $this->data[$offset];
        } else {
            return $this->data[$offset];
        }

        return $copy;
    }

    /**
     * Checks if a config `$offset` exists.
     *
     * @param mixed $offset Configuration offset.
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->data);
    }

    /**
     * Unsets a config using the `$offset`.
     *
     * @param mixed $offset Configuration offset.
     *
     * @throws RuntimeModificationException
     */
    public function offsetUnset($offset)
    {
        if (!$this->allowModifications()) {
            throw new RuntimeModificationException('Cannot modify configs in read only mode.');
        }

        unset($this->data[$offset]);
    }

    /**
     * Checks if configs modifications are allowed.
     *
     * This prevents modifications on configs at runtime if disabled.
     *
     * @return bool
     */
    protected function allowModifications()
    {
        return $this->allowModifications;
    }

    /**
     * Recursively counts the number of configs.
     *
     * @param array $data
     *
     * @return int
     */
    protected function recCount(array $data)
    {
        $count = 0;

        foreach ($data as $_data) {
            $count++;

            if (is_array($_data)) {
                $count += $this->recCount($_data);
            }
        }

        return $count;
    }

    /**
     * Sets a config.
     *
     * @param array $keys  Keys to match
     * @param mixed $value Config value
     *
     * @return mixed
     */
    protected function innerSet(array $keys, $value)
    {
        $currentItem = null;
        $count = count($keys);

        /*************************************************************************
         * If `$keys` contains only one key, we return the `$array`              *
         * data associated to that key.                                          *
         *                                                                       *
         * If $keys contains more then one key, we access                        *
         * and stores the first `&$data[$key]` value (it should be an array)     *
         * to the `&$currentItem`, and we do the same with the remaining keys    *
         * until they finish. The last `$currentItem` is the item we want change *
         ************************************************************************/
        foreach ($keys as $index => $key) {
            $key = $keys[$index];

            if ($index == 0) {
                $currentItem = &$this->data[$key];
            } else {
                $currentItem = &$currentItem[$key];
            }
        }

        $currentItem = $value;
    }

    /**
     * Gets the config value.
     *
     * @param array $keys Keys to match.
     *
     * @return mixed
     */
    protected function innerGet(array $keys)
    {
        $currentItem = null;

        /************************************************************************
         * If `$keys` contains only one key, we return the `$array`             *
         * data associated to that key.                                         *
         *                                                                      *
         * If $keys contains more then one key, we access                       *
         * and stores the first `$data[$key]` value (it should be an array)     *
         * to the `$currentItem`, and we do the same with the remaining of keys *
         * until they finish. The last `$key` contains the value.               *
         ************************************************************************/
        foreach ($keys as $index => $key) {
            $key = $keys[$index];

            if ($index == 0) {
                $currentItem = $this->data[$key];
            } else {
                $currentItem = $currentItem[$key];
            }
        }

        return $currentItem;
    }

    /**
     * Checks if a config exists.
     *
     * @param array $keys Keys to match
     *
     * @return bool
     */
    protected function innerHas(array $keys)
    {
        $currentItem = null;
        $count = count($keys);

        /*****************************************************************
         * If `$keys` contains only one key, we just                     *
         * returns if that key exists.                                   *
         *                                                               *
         * If `$keys` contains more then one key, we first check         *
         * if the first key exists, if not, we return false              *
         * immediately, else, we keep checking if the remaining keys       *
         * exists until we find one that does'nt exists and return false *
         * or return true if all keys exists.                            *
         *****************************************************************/
        foreach ($keys as $index => $key) {
            $key = $keys[$index];

            if ($index == 0) {
                if (!array_key_exists($key, $this->data)) {
                    return false;
                }

                $currentItem = $this->data[$key];
            } else {
                if (!array_key_exists($key, $currentItem)) {
                    return false;
                }

                $currentItem = $currentItem[$key];
            }

            if (($index + 1) == $count) {
                return true;
            }
        }
    }

    /**
     * Unsets a config.
     *
     * @param array $keys Keys to match
     *
     * @return mixed
     */
    protected function innerUnset(array $keys)
    {
        $oldItem = null;
        $currentItem = null;
        $count = count($keys);

        /************************************************************************
         * If `$keys` contains only one key, we return the `$array`             *
         * data associated to that key.                                         *
         *                                                                      *
         * If $keys contains more then one key, we access                       *
         * and stores the first `&$data[$key]` value (it should be an array)    *
         * to the `&$currentItem`, and we do the same with the remaining keys   *
         * until they finish. The last `$currentItem` is what we want           *                                                            *
         ************************************************************************/
        foreach ($keys as $index => $key) {
            $key = $keys[$index];

            if ($index == 0) {
                $currentItem = &$this->data[$key];
            } else {
                $oldItem = &$currentItem;

                $currentItem = &$currentItem[$key];
            }
        }

        /**
         * Since unset variable by reference only unsets
         * the variable inside the function scope, we'll
         * use a simple hack. Make a copy of the original
         * array, unsets the item we want unset, and replaces
         * the old item with the new item containing the fresh
         * changes.
         */
        $copy = $oldItem;

        unset($copy[$key]);

        $oldItem = $copy;
    }
}
