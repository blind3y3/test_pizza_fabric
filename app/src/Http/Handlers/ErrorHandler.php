<?php

declare(strict_types=1);

namespace App\Http\Handlers;

use Exception;
use Respect\Validation\Exceptions\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ErrorHandler
{
    public function handle(Exception $exception): JsonResponse
    {
        if ($exception instanceof ValidationException) {
            return new JsonResponse([
                'message' => $exception->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
        return new JsonResponse([
            'message' => $exception->getMessage(),
        ], $exception->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}