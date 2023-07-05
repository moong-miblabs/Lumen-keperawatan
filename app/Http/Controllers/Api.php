<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\RespondensModel;

class Api extends Controller{
    
    public function __construct(){
        //
    }

    public function login(Request $request){
        $username = $request->input('username');
        $password = $request->input('password');
        try {
            $data = RespondensModel::findOne(['username_responden'=>$username]);
            if($data){
                if ($password == $data->password_responden) {
                    return new Response($data->nama_responden, 200);
                } else {
                    return new Response('fail', 400);
                }
            } else {
                return new Response('fail', 400);
            }
        } catch(\Exception $e) {
            $res = new \stdClass();
            $res->error_code = 5;
            $res->error_desc = 'Internal Server Error';
            $res->data = $e;
            return response()->json($res,500);
        }
    }

    public function testMiddleware(Request $request){
        $dataResponden = $request->input('dataResponden');
        return response()->json($dataResponden,200);
    }
}
