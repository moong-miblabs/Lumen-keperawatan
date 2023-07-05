<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\SetupModel;
use App\Models\UsersModel;

class Setup extends Controller{
    
    public function __construct(){
        //
    }

    public function dbsync(){
        try {
            SetupModel::dbsync();
            $res = new \stdClass();
            $res->error_code = 0;
            $res->error_desc = '';
            $res->data = [];
            return response()->json($res,200);
        } catch(\Exception $e) {
            $res = new \stdClass();
            $res->error_code = 5;
            $res->error_desc = 'Internal Server Error';
            $res->data = $e;
            return response()->json($res,500);
        }
    }

    public function seed(){
        try {
            SetupModel::seed();
            $res = new \stdClass();
            $res->error_code = 0;
            $res->error_desc = '';
            $res->data = [];
            return response()->json($res,200);
        } catch(\Exception $e) {
            $res = new \stdClass();
            $res->error_code = 5;
            $res->error_desc = 'Internal Server Error';
            $res->data = $e;
            return response()->json($res,500);
        }
    }

    public function drop(){
        try {
            SetupModel::drop();
            $res = new \stdClass();
            $res->error_code = 0;
            $res->error_desc = '';
            $res->data = [];
            return response()->json($res,200);
        } catch(\Exception $e) {
            $res = new \stdClass();
            $res->error_code = 5;
            $res->error_desc = 'Internal Server Error';
            $res->data = $e;
            return response()->json($res,500);
        }
    }

    public function test(){
        $where  = ['nama_user'=>'MAMAN'];
        try {
            $data = UsersModel::find();
            $res = new \stdClass();
            $res->error_code = 0;
            $res->error_desc = '';
            $res->data = $data;
            return response()->json($res,200);
        } catch(\Exception $e) {
            return var_dump($e);
            // $res = new \stdClass();
            // $res->error_code = 5;
            // $res->error_desc = 'Internal Server Error';
            // $res->data = $e;
            // return response()->json($res,500);
        }
    }
}
