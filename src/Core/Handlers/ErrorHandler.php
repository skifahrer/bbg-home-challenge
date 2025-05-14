<?php

declare(strict_types=1);

namespace App\Core\Handlers;

use App\Core\Exceptions\AbstractApiErrorException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\NoConfigurationException;

class ErrorHandler
{
    public function handleException(\Throwable|\Exception $e): Response
    {
        $message = "Internal Server Error, please contact the administrator!";
        $errorCode = 501;

        if ($e instanceof NoConfigurationException) {
            $message = "requested url doesn't exist on this server!";
            $errorCode = 404;
        }

        if ($e instanceof AbstractApiErrorException) {
            $message = $e->getMessage();
            $errorCode = $e->getCode();
        }

        return new JsonResponse(
            [
                'errors' => [
                    $message,
                ]
            ],
            $errorCode,
        );
    }
}
