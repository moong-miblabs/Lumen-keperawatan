<?php

namespace App\Http\Middleware;

use Closure;

class ApiPostMiddleware{
    public function handle($request, Closure $next){
        $username = $request->input('id');
        return $next($request);
    }
}
