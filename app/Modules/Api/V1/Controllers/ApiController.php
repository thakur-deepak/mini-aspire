<?php

namespace App\Modules\Api\V1\Controllers;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    private $errors;

    private $message;

    private $http_code;

    public function createJsonResponse($result, $message = '')
    {
        return [
            'data' => $result,
            'message' => $message,
        ];
    }

    public function showBadRequestError($validation_error, $error_message, $http_error_code = 400)
    {
        if ($http_error_code == 400 && ! empty($validation_error)) {
            $errors = is_object($validation_error) ? json_decode($validation_error, true) : $validation_error;
            $error_msg = [];
            foreach ($errors as $error) {
                $error_msg[] = is_array($error) ? implode(',', array_values($error)) : $error;
            }

            $error_message = implode(',', array_values($error_msg));
        }
        throw new ApiException($validation_error, $error_message, $http_error_code);
    }

    public function showSuccessRequest($data_set, $message, $http_code)
    {
        $response_data = $this->createJsonResponse($data_set, $message);
        return new JsonResponse($response_data, $http_code);
    }

    public function showBadRequest()
    {
        $response_data = $this->createJsonResponse($this->errors, $this->message);
        return new JsonResponse($response_data, $this->http_code);
    }

    public function sendErrorResponse($data_set, $message, $http_code)
    {
        throw new ApiException($data_set, $message, $http_code);
    }

    protected function arrayMergeRecursiveDistinct(array &$response, array &$skelton)
    {
        $merged = $response;
        foreach ($skelton as $key => &$value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = $this->arrayMergeRecursiveDistinct($merged[$key], $value);
            } else {
                if (! isset($merged[$key])) {
                    $merged[$key] = $value;
                }
            }
        }
        return $merged;
    }
}
