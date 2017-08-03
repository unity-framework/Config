<?php

namespace Unity\Component\Configuration\Drivers\ArrayFile\Exceptions;

use Exception;
use Throwable;

class BadConfigStringException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}