<?php

declare(strict_types=1);

namespace App\Infrastructure\Helper;

use App\Dictionary\Response\ResponseStatus;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiResponseHelper
{
    final public const SUCCESS = 'ok';

    final public const FAILED = 'failed';

    public const ERROR_PARAMETER_VALIDATION_MESSAGE = 'Validation errors.';

    public static function successResponse(?array $data, int $statusCode = JsonResponse::HTTP_OK): JsonResponse
    {
        return new JsonResponse(['status' => self::SUCCESS, 'data' => $data], $statusCode);
    }

    public static function errorResponse(
        string $message,
        ?array $errors,
        int $statusCode = JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
    ): JsonResponse {
        return new JsonResponse(['status' => self::FAILED, 'message' => $message, 'errors' => $errors], $statusCode);
    }

    public static function validationErrorResponse(array $errors): JsonResponse
    {
        return self::errorResponse(
            self::ERROR_PARAMETER_VALIDATION_MESSAGE,
            $errors,
            JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
        );
    }
}
