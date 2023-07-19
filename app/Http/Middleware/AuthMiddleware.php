<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

use App\Helper\JsonwebtokenHelper;

class AuthMiddleware{
    public function handle($request, Closure $next){
        $token = $request->header('token');
        if(!$token) {
            $res = new \stdClass();
            $res->error_code = 4;
            $res->error_desc = 'Unauthorized';
            $res->data = [];
            return response()->json($res,400);
        }
        try {
            $decoded = JsonwebtokenHelper::verify($token);
            if($decoded){
                $request->merge(['dataToken' => (array) $decoded]);
                return $next($request);
            } else {
                $res = new \stdClass();
                $res->error_code = 4;
                $res->error_desc = 'Unauthorized';
                $res->data = [];
                return response()->json($res,400);
            }
        } catch(\Exception $e) {
            return $e;
            $res = new \stdClass();
            $res->error_code = 5;
            $res->error_desc = 'Internal Server Error';
            $res->data = $e;
            return response()->json($res,500);
        }
    }
}
