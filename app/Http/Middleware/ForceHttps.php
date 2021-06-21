<?php

namespace App\Http\Middleware;

use Closure;

class ForceHttps
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
        if (
            (!$request->secure() && $request->header('x-forwarded-proto') != 'https')
            && config('app.force_https')
        ) {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}
