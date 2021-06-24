<?php

namespace App\Formatter;

use Illuminate\Http\JsonResponse;

interface OutputInterface
{
    public function getStatusCode(): int;

    public function setStatusCode(int $status_code): object;

    public function respondWithNoContent(): JsonResponse;

    public function respondWithArray(array $array, array $headers): JsonResponse;

    public function sendJsonResponse($data, string $message, int $http_code = 200): JsonResponse;

    public function respondWithError(string $message, $errors): JsonResponse;

    public function errorInternal(string $message, $errors): JsonResponse;

    public function errorNotFound(string $message, $errors): JsonResponse;

    public function errorUnauthorized(string $message, $errors): JsonResponse;

    public function errorForbidden(string $message, $errors): JsonResponse;

    public function errorValidation(string $message, $errors): JsonResponse;
}
