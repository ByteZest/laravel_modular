<?php

namespace App\Application\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForceAcceptJson
{
    public function handle(Request $request, Closure $next)
    {
        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}
