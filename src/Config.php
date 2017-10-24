<?php

namespace Unity\Component\Config;

use ArrayAccess;
use Countable;
use Unity\Contracts\Config\IConfig;

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
     * @param $data array Contains configurations data.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Gets a configuration value.
     *
     * @param $config
     *
     * @return mixed
     */
    public function get($config)
    {
        $keys = $this->denote($config);

        return $this->getConfig($keys, $this->data);
    }

    /**
     * Checks ifs a configuration exists.
     *
     * @param $config
     *
     * @return bool
     */
    public function has($config)
    {
        $keys = $this->denote($config);

        return $this->hasConfig($keys, $this->data);
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
     * Whether a offset exists.
     *
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset <p>
     *                      An offset to check for.
     *                      </p>
     *
     * @return bool true on success or false on failure.
     *              </p>
     *              <p>
     *              The return value will be casted to boolean if non-boolean was returned.
     *
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        // TODO: Implement offsetExists() method.
    }

    /**
     * Offset to retrieve.
     *
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param mixed $offset <p>
     *                      The offset to retrieve.
     *                      </p>
     *
     * @return mixed Can return all value types.
     *
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        // TODO: Implement offsetGet() method.
    }

    /**
     * Offset to set.
     *
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $value  <p>
     *                      The value to set.
     *                      </p>
     *
     * @return void
     *
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        // TODO: Implement offsetSet() method.
    }

    /**
     * Offset to unset.
     *
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param mixed $offset <p>
     *                      The offset to unset.
     *                      </p>
     *
     * @return void
     *
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
    }

    /**
     * Count elements of an object.
     *
     * @link http://php.net/manual/en/countable.count.php
     *
     * @return int The custom count as an integer.
     *             </p>
     *             <p>
     *             The return value is cast to an integer.
     *
     * @since 5.1.0
     */
    public function count()
    {
        return count($this->data);
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
     * Gets the configuration value.
     *
     * @param array $keys Keys to match
     * @param array $data The top most array
     *
     * @return mixed
     */
    protected function getConfig(array $keys, array $data)
    {
        $matchedData = null;
        $count = count($keys);

        /************************************************************************
         * If `$keys` contains only one key, we return the `$array`             *
         * data associated to that key.                                         *
         *                                                                      *
         * If $keys contains more then one key, we access                       *
         * and stores the first `$data[$key]` value (it should be an array)     *
         * to the `$matchedData`, and we do the same with the remaining of keys *
         * until they finish. The last `$key` contains the value.               *
         ************************************************************************/
        for ($i = 0; $i < $count; $i++) {
            $key = $keys[$i];

            if ($i == 0) {
                $matchedData = $data[$key];
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
     * @param array $data The top most array
     *
     * @return bool
     */
    protected function hasConfig(array $keys, array $data)
    {
        $matchedData = null;
        $count = count($keys);

        /*****************************************************************
         * If `$keys` contains only one key, we just                     *
         * returns if that key exists.                                   *
         *                                                               *
         * If `$keys` contains more then one key, we first check         *
         * if the first key exists, if not, we return false              *
         * imediatly, else, we keep checking if the remaining keys       *
         * exists until we find one that does'nt exists and return false *
         * or return true if all keys exists.                            *
         *****************************************************************/
        for ($i = 0; $i < $count; $i++) {
            $key = $keys[$i];

            if ($i == 0) {
                if (!array_key_exists($key, $matchedData)) {
                    return false;
                }

                $matchedData = $matchedData[$key];
            } else {
                if (!array_key_exists($key, $matchedData)) {
                    return false;
                }

                $matchedData = $matchedData[$key];
            }

            if (($i + 1) == $count) {
                return true;
            }
        }
    }
}
