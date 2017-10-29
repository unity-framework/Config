<?php

namespace Unity\Component\Config;

use Countable;
use ArrayAccess;
use Unity\Contracts\Config\IConfig;
use Unity\Component\Config\Exceptions\ConfigRuntimeException;

/**
 * Class Config.
 *
 * Configurations manager.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 * @link   https://github.com/e200/
 */
class Config implements IConfig, ArrayAccess, Countable
{
    /** @var array */
    protected $data;

    /** @var bool */
    protected $readOnlyMode;

    /**
     * @param array $data Contains configurations data.
     * @param bool $readOnlyMode Enable or disable read only mode.
     */
    public function __construct(array $data, $readOnlyMode = true)
    {
        $this->data = $data;
        $this->readOnlyMode = $readOnlyMode;
    }

    /**
     * Sets a configuration value at runtime.
     *
     * @param string $config dot notation string that references the
     *                configuration to be replaced.
     * @param mixed $value The new value.
     *
     * @return static
     *
     * @throws ConfigRuntimeException
     */
    public function set($config, $value)
    {
        if ($this->isOnReadOnlyMode()) {
            throw new ConfigRuntimeException('Cannot modify configurations in read only mode.');            
        }

        $keys = $this->denote($config);

        $this->innerSet($keys, $value);
        
        return $this;
    }

    /**
     * Gets a configuration value.
     *
     * @param string $config A dot notation string that references the
     *                configuration to be getted.
     *
     * @return mixed
     */
    public function get($config)
    {
        $keys = $this->denote($config);

        return $this->innerGet($keys);
    }

    /**
     * Checks ifs a configuration exists.
     *
     * @param string $config A dot notation string that references the
     *                configuration to be checked.
     *
     * @return bool
     */
    public function has($config)
    {
        $keys = $this->denote($config);

        return $this->innerHas($keys);
    }
    
    /**
     * Counts the number of configurations.
     *
     * @return int
     */
    public function count()
    {
        return $this->recCount($this->data);
    }

    /**
     * Gets all available configurations.
     *
     * @return array
     */
    public function getAll()
    {
        return $this->data;
    }

    /**
     * Sets a configuration using the `$offset`.
     *
     * @param mixed $offset Configuration offset.
     * @param mixed $value The new value.
     * 
     * @throws ConfigRuntimeException
     */
    public function offsetSet($offset, $value)
    {
        if ($this->isOnReadOnlyMode()) {
            throw new ConfigRuntimeException('Cannot modify configurations in read only mode.');            
        }

        $this->data[$offset] = $value;
    }

    /**
     * Gets a configuration using the `$offset`.
     *
     * @param mixed $offset Configuration offset.
     *
     * @return mixed
     */
    public function &offsetGet($offset)
    {
        /**
         * We're returning configurations
         * by reference, that can let
         * anyone modify configurations
         * data.
         * 
         * But if read only mode is enabled,
         * we can't let anyone modify configurations
         * so, to prevent this, we'll return
         * a copy of `$this->data` and if someone
         * modify that returned copy, these changes
         * will not be reflected to `$this->data`. 
         */
        if ($this->isOnReadOnlyMode()) {
            $copy = $this->data[$offset];
        } else {
            return $this->data[$offset];
        }

        return $copy;
    }

    /**
     * Checks if a configuration `$offset` exists.
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
     * Unsets a configuration using the `$offset`.
     *
     * @param mixed $offset Configuration offset.
     * 
     * @throws ConfigRuntimeException
     */
    public function offsetUnset($offset)
    {
        if ($this->isOnReadOnlyMode()) {
            throw new ConfigRuntimeException('Cannot modify configurations in read only mode.');            
        }

        unset($this->data[$offset]);        
    }
    
    /**
     * Denotes a string using dot (.) as separator.
     *
     * @param string $notation
     *
     * @return string[]
     */
    protected function denote($notation)
    {
        return explode('.', $notation);
    }

    /**
     * Checks if configurations read only mode is enabled.
     * 
     * This prevents modifications on configurations at runtime.
     * 
     * @return bool
     */
    protected function isOnReadOnlyMode()
    {
        return $this->readOnlyMode;
    }

    /**
     * Recursively counts the number of configurations.
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
     * Sets the configuration value.
     *
     * @param array $keys Keys to match
     * @param mixed $value The top most array
     *
     * @return mixed
     */
    protected function innerSet(array $keys, $value)
    {
        $matchedData = null;
        $count = count($keys);

        /************************************************************************
         * If `$keys` contains only one key, we return the `$array`             *
         * data associated to that key.                                         *
         *                                                                      *
         * If $keys contains more then one key, we access                       *
         * and stores the first `&$data[$key]` value (it should be an array)    *
         * to the `&$matchedData`, and we do the same with the remaining keys   *
         * until they finish. The last `$matchedData` is where we must set our  *
         * `$value`.                                                            *
         ************************************************************************/
        foreach ($keys as $index => $key) {
            $key = $keys[$index];

            if ($index == 0) {
                $matchedData = &$this->data[$key];
            } else {
                $matchedData = &$matchedData[$key];
            }
            
            if (($index + 1) == $count) {
                $matchedData = $value;
            }
        }

        return $matchedData;
    }

    /**
     * Gets the configuration value.
     *
     * @param array $keys Keys to match.
     *
     * @return mixed
     */
    protected function innerGet(array $keys)
    {
        $matchedData = null;

        /************************************************************************
         * If `$keys` contains only one key, we return the `$array`             *
         * data associated to that key.                                         *
         *                                                                      *
         * If $keys contains more then one key, we access                       *
         * and stores the first `$data[$key]` value (it should be an array)     *
         * to the `$matchedData`, and we do the same with the remaining of keys *
         * until they finish. The last `$key` contains the value.               *
         ************************************************************************/
        foreach ($keys as $index => $key) {
            $key = $keys[$index];

            if ($index == 0) {
                $matchedData = $this->data[$key];
            } else {
                $matchedData = $matchedData[$key];
            }
        }

        return $matchedData;
    }

    /**
     * Checks if a configuration exists.
     *
     * @param array $keys Keys to match
     *
     * @return bool
     */
    protected function innerHas(array $keys)
    {
        $matchedData = null;
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

                $matchedData = $this->data[$key];
            } else {
                if (!array_key_exists($key, $matchedData)) {
                    return false;
                }

                $matchedData = $matchedData[$key];
            }

            if (($index + 1) == $count) {
                return true;
            }
        }
    }
}
