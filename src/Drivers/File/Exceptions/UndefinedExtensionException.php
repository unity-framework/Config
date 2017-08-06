<?php
/**
 * Created by PhpStorm.
 * User: logik
 * Date: 06-08-2017
 * Time: 21:07
 */

namespace Unity\Component\Config\Drivers\File\Exceptions;


use Exception;
use Throwable;

class UndefinedExtensionException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}