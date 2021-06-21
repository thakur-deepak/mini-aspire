<?php

namespace App\Traits;

use Throwable;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\JsonResponse;

trait RestExceptionHandler
{
    protected $exception;

    protected function getJsonResponseForException(Throwable $exception)
    {
        $this->exception = $exception;
        switch (true) {
            case ($this->exception instanceof ValidationException):
                return $this->validation();

            case ($this->exception instanceof MethodNotAllowedHttpException):
                return $this->MethodNotAllowedHttp();

            case ($this->exception instanceof NotFoundHttpException):
                return $this->NotFoundHttp();

            default:
                return $this->serverError();
        }
    }

    protected function validation()
    {
        return $this->response(__('messages.validation_error'), 400, $this->exception->validator->errors()->getMessages());
    }

    protected function methodNotAllowedHttp()
    {
        return $this->response(__('method_not_allowed'), 404, $this->exception->getMessage());
    }

    protected function notFoundHttp()
    {
        return $this->response(__('not_found'), 400, $this->exception->getMessage());
    }

    protected function invalidHeaders()
    {
        return $this->response(__('invalid_headers'), 400);
    }

    protected function invalidToken()
    {
        return $this->response(__('invalid_token'), 404);
    }

    protected function response($message, $http_code, $data = '')
    {
        return new JsonResponse(
            [
                'message' => $message,
                'data' => $data
            ],
            $http_code
        );
    }

    protected function serverError()
    {
        return $this->response(__('not_found'), 500, $this->exception->getMessage());
    }
}
