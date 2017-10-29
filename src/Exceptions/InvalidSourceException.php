<?php

namespace Unity\Component\Config\Exceptions;

use Exception;
use Throwable;

/**
 * Class InvalidSourceException.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 * @link   https://github.com/e200/
 */
class InvalidSourceException extends Exception
{
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
