<?php

namespace App\Http\Middleware;

use Closure;

class JsonMiddleware
{
    public function handle($request, Closure $next)
    {
        $request->headers->set('accept', 'application/json', true);

        return $next($request);
    }
}
