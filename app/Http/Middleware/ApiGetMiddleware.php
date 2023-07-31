<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

use App\Models\RespondensModel;

class ApiGetMiddleware{
    public function handle($request, Closure $next){
        $username = $request->query('id');
        try {
            $data = RespondensModel::findOne(['username_responden'=>$username]);
            if($data){
                $request->merge(['dataResponden' => (array) $data]);
                return $next($request);
            } else {
                $res = new \stdClass();
                $res->error_code = 4;
                $res->error_desc = 'Unauthorized';
                $res->data = [];
                return response()->json($res,200);
            }
        } catch(\Exception $e) {
            $res = new \stdClass();
            $res->error_code = 5;
            $res->error_desc = 'Internal Server Error';
            $res->data = $e;
            return response()->json($res,200);
        }
    }
}
