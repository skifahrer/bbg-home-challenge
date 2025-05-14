<?php

declare(strict_types=1);

namespace App\Core\Exceptions;

class PageNotFoundException extends \Exception
{
    /** The error message */
    protected $message = "requested url doesn't exist on this server!";

    /** The error code */
    protected $code = 404;
}