<?php

declare(strict_types=1);

namespace App\Core\Exceptions;

class InternalErrorException extends \Exception
{
    /** The error message */
    protected $message = "Internal Error occurred pls contact the administrator!";

    /** The error code */
    protected $code = 501;
}
