<?php

namespace App\Modules\Api\V1\Controllers;

use App\Formatter\OutputInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    public function sendJsonResponse($keys, $message, $response_code = 200): JsonResponse
    {
        return resolve(OutputInterface::class)->sendJsonResponse(
            $keys,
            $message,
            $response_code
        );
    }
}
