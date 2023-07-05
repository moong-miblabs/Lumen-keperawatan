<?php

namespace App\Http\Middleware;

use Closure;

class ApiGetMiddleware{
    public function handle($request, Closure $next){
        $username = $request->query('id');
        return $next($request);
    }
}
