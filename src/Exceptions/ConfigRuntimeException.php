<?php

namespace Unity\Component\Config\Exceptions;

use RuntimeException;
use Throwable;

/**
 * Class ConfigRuntimeException.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 * @link   https://github.com/e200/
 */
class ConfigRuntimeException extends RuntimeException
{
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
