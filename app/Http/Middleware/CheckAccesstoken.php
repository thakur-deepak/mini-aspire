<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\V2\UserAccessToken;
use App\Models\User;
use App\Traits\RestApiAuthentication;

class CheckAccesstoken
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user_id = $request->id ?? 0;
        $access_token = trim($request->bearerToken());
        $user_access_details = (new UserAccessToken())->getByAccessToken($access_token);

        if (! $user_access_details) {
            throw new \App\Exceptions\InvalidAccessTokenException();
        }

        if (! empty($user_access_details->user_id)) {
            $user = (new User())->fetchDataWithSelectedFields(
                '*',
                ['id' => $user_access_details->user_id],
                'first'
            );
            if (! $user) {
                throw new \App\Exceptions\InvalidAccessTokenException();
            }
        }

        if (! $user_id) {
            RestApiAuthentication::setAuthorizedUser($user);
            return $next($request);
        }

        if (isset($access_token) && ($user) && ($user->id == $user_id)) {
            RestApiAuthentication::setAuthorizedUser($user);
            return $next($request);
        }
        throw new \App\Exceptions\InvalidAccessTokenException();
    }
}
