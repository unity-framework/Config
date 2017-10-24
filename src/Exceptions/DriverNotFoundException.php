<?php

namespace Unity\Component\Config\Exceptions;

use Exception;
use Throwable;

class DriverNotFoundException extends Exception
{
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
