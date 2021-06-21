<?php

namespace App\Http\Middleware;

use App\Components\RoleHandler;
use Closure;

class IsAuthorized
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $auth = new RoleHandler($request);
        if ($auth->validate()) {
            return $next($request);
        }
        return response()->json(
            ['message' => __('messages.failure_messages.not_authorized'),
            ],
            401
        );
    }
}
