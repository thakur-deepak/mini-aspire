<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait RestApiAuthentication
{

    private static $authorized_user = null;

    protected function isApiCall(Request $request)
    {
        return strpos($request->getUri(), '/api/v') !== false;
    }
}
