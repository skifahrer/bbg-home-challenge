<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Core\Exceptions\AbstractApiErrorException;

class ProductNotFoundExceptionAbstract extends AbstractApiErrorException
{
    protected $message = 'Product not found';
    protected $code = 404;
}