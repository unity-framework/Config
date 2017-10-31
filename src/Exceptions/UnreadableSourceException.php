<?php

namespace Unity\Component\Config\Exceptions;

use Exception;
use Throwable;

/**
 * Class UnreadableSourceException.
 *
 * @author Eleandro Duzentos <eleandro@inbox.ru>
 * @link   https://github.com/e200/
 */
class UnreadableSourceException extends Exception
{
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
