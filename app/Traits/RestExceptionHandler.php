<?php

namespace App\Traits;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\JsonResponse;

trait RestExceptionHandler
{
    protected $exception;

    protected function getJsonResponseForException(Throwable $exception): JsonResponse
    {
        $this->exception = $exception;
        switch (true) {
            case ($this->exception instanceof AuthorizationException):
                return $this->authorizationException();

            case ($this->exception instanceof ValidationException):
                return $this->validation();

            case ($this->exception instanceof MethodNotAllowedHttpException):
                return $this->MethodNotAllowedHttp();

            case ($this->exception instanceof NotFoundHttpException):
                return $this->NotFoundHttp();

            case ($this->exception instanceof AuthenticationException):
                return $this->authError();

            case ($this->exception instanceof HttpException):
                return $this->apiError();

            default:
                return $this->serverError();
        }
    }

    protected function apiError(): JsonResponse
    {
        $message = $this->exception->getMessage();
        $error = json_decode($message, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $this->response(__('messages.error.unauthorized'), 403, null, $error);
        }
        return $this->response(__('messages.error.unauthorized'), 403, $message);
    }

    protected function authorizationException(): JsonResponse
    {
        return $this->response(__('messages.error.unauthorized'), 403, $this->exception->getMessage());
    }

    protected function validation(): JsonResponse
    {
        return $this->response(__('messages.validation_error'), 400, $this->exception->validator->errors()->getMessages());
    }

    protected function methodNotAllowedHttp(): JsonResponse
    {
        return $this->response(__('method_not_allowed'), 404, $this->exception->getMessage());
    }

    protected function notFoundHttp(): JsonResponse
    {
        return $this->response(__('not_found'), 400, $this->exception->getMessage());
    }

    protected function invalidHeaders(): JsonResponse
    {
        return $this->response(__('invalid_headers'), 400);
    }

    protected function invalidToken(): JsonResponse
    {
        return $this->response(__('invalid_token'), 404);
    }

    protected function response($message, $http_code, $data = ''): JsonResponse
    {
        return new JsonResponse(
            [
                'message' => $message,
                'data' => $data
            ],
            $http_code
        );
    }

    protected function serverError(): JsonResponse
    {
        return $this->response(__('not_found'), 500, $this->exception->getMessage());
    }

    protected function authError(): JsonResponse
    {
        return $this->response($this->exception->getMessage(), 401);
    }
}
