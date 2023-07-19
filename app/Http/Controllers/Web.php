<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\UsersModel;

use App\Helper\BcryptHelper;
use App\Helper\JsonwebtokenHelper;

class Web extends Controller{
    
    public function __construct(){
        //
    }

    public function login(Request $request){
        $username = $request->input("username");
        $password = $request->input("password");
        try {
            $data = UsersModel::findOne(['username_user'=>$username]);
            if($data) {
                if(BcryptHelper::compare($password,$data->password_user)) {
                    $res = new \stdClass();
                    $res->error_code    = 0;
                    $res->error_desc    = '';
                    $res->data          = [];
                    $res->token         = JsonwebtokenHelper::sign(['id'=>$data->id]);
                    return response()->json($res,200);
                } else {
                    $res = new \stdClass();
                    $res->error_code    = 4;
                    $res->error_desc    = 'Password salah.';
                    $res->data          = [];
                    return response()->json($res,200);
                }
            } else {
                $res = new \stdClass();
                $res->error_code    = 4;
                $res->error_desc    = 'Username tidak ditemukan.';
                $res->data          = [];
                return response()->json($res,200);
            }
        } catch(\Execption $e) {
            $res = new \stdClass();
            $res->error_code    = 5;
            $res->error_desc    = 'Internal Server Error';
            $res->data          = $e;
            return response()->json($res,500);
        }
    }

    public function verify(Request $request){
        $token = $request->input("token");
        try {
            $decode = JsonwebtokenHelper::verify($token);
            return response()->json($decode,200);
        } catch(\Execption $e) {
            $res = new \stdClass();
            $res->error_code    = 5;
            $res->error_desc    = 'Internal Server Error';
            $res->data          = $e;
            return response()->json($res,500);
        }
    }

    public function home(Request $request){
        $dataToken = $request->input('dataToken');
        return response()->json($dataToken,200);
    }

}
