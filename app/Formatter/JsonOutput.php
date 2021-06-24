<?php

namespace App\Formatter;

use Illuminate\Http\JsonResponse;

class JsonOutput implements OutputInterface
{
    protected $status_code = 200;

    public function getStatusCode(): int
    {
        return $this->status_code;
    }

    public function setStatusCode(int $statusCode): object
    {
        $this->status_code = $statusCode;
        return $this;
    }

    public function respondWithNoContent(): JsonResponse
    {
        return new JsonResponse([], 204);
    }

    public function respondWithArray(array $array, array $headers = []): JsonResponse
    {
        return new JsonResponse($array, $this->status_code, $headers);
    }

    public function sendJsonResponse($data, string $message, int $http_code = 200): JsonResponse
    {
        return $this->setStatusCode($http_code)
            ->respondWithArray(
                [
                    'data' => $data,
                    'message' => $message,
                ]
            );
    }

    public function respondWithError(string $message, $errors = []): JsonResponse
    {
        return $this->respondWithArray(
            [
                'errors'  => $errors,
                'message' => $message,
            ]
        );
    }

    public function errorInternal(string $message = 'Internal Error', $errors = []): JsonResponse
    {
        return $this->setStatusCode(500)
            ->respondWithError($message, $errors);
    }

    public function errorNotFound(string $message = 'Resource Not Found', $errors = []): JsonResponse
    {
        return $this->setStatusCode(404)
            ->respondWithError($message, $errors);
    }

    public function errorUnauthorized(string $message = 'Unauthorized', $errors = []): JsonResponse
    {
        return $this->setStatusCode(401)
            ->respondWithError($message, $errors);
    }

    public function errorForbidden(string $message = 'Forbidden', $errors = []): JsonResponse
    {
        return $this->setStatusCode(403)
            ->respondWithError($message, $errors);
    }

    public function errorValidation(string $message = 'Validation Error', $errors = []): JsonResponse
    {
        return $this->setStatusCode(400)
            ->respondWithError($message, $errors);
    }
}
