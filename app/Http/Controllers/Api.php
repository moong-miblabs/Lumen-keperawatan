<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\RespondensModel;

use App\Helper\BcryptHelper;

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
                if (BcryptHelper::compare($password,$data->password_responden)) {
                    return new Response(ucwords(strtolower($data->nama_responden)), 200);
                } else {
                    return new Response('fail', 200);
                }
            } else {
                return new Response('fail', 200);
            }
        } catch(\Exception $e) {
            $res = new \stdClass();
            $res->error_code = 5;
            $res->error_desc = 'Internal Server Error';
            $res->data = $e;
            return response()->json($res,200);
        }
    }

    public function testMiddleware(Request $request){
        $dataResponden = $request->input('dataResponden');
        return response()->json($dataResponden,200);
    }
}
