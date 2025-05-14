<?php

namespace App\Core\Exceptions;

use Exception;

abstract class AbstractApiErrorException extends Exception
{
    protected $code = 501;
}
