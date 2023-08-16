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
            $collection = RespondensModel::findOne(['username_responden'=>$username]);
            $data = $collection->toArray();
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
            return new Response($e->getMessage(), 200);
        }
    }

    public function testMiddleware(Request $request){
        $dataResponden = $request->input('dataResponden');
        return response()->json($dataResponden,200);
    }
}
