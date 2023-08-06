<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

// use Illuminate\Support\Facades\DB;

use App\Models\UsersModel;
use App\Models\RespondensModel;
use App\Models\DemografiModel;
use App\Models\RespondenDemografiModel;

use App\Helper\BcryptHelper;
use App\Helper\JsonwebtokenHelper;

class Main extends Controller{
    
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
                    $res->data          = $data;
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
            $res->data          = $e->getMessage();
            return response()->json($res,200);
        }
    }

    public function verify(Request $request){
        $token = $request->input("token");
        try {
            $decode = JsonwebtokenHelper::verify($token);
            if($decode) {
                return new Response(true,200);
            } else {
                return new Response(false,200);
            }
            return response()->json($decode,200);
        } catch(\Execption $e) {
            $res = new \stdClass();
            $res->error_code    = 5;
            $res->error_desc    = 'Internal Server Error';
            $res->data          = $e->getMessage();
            return response()->json($res,200);
        }
    }

    public function home(Request $request){
        $dataToken = $request->input('dataToken');
        return response()->json($dataToken,200);
    }

    public function listResponden(Request $request){
        $dataToken = $request->input('dataToken');
        try {
            $collection = RespondenDemografiModel::findAll();
            $data = $collection->toArray();
            if($data) {
                $res = new \stdClass();
                $res->error_code    = 0;
                $res->error_desc    = '';
                $res->data          = $data;
                return response()->json($res,200);
            } else {
                $res = new \stdClass();
                $res->error_code    = 1;
                $res->error_desc    = 'No Content';
                $res->data          = [];
                return response()->json($res,200);
            }
        } catch(\Execption $e) {
            $res = new \stdClass();
            $res->error_code    = 5;
            $res->error_desc    = 'Internal Server Error';
            $res->data          = $e->getMessage();
            return response()->json($res,200);
        }
    }

    public function detailResponden(Request $request, $id){
        $dataToken = $request->input('dataToken');
        try {
            $data = RespondenDemografiModel::findOne(['id'=>$id]);
            if($data) {
                $res = new \stdClass();
                $res->error_code    = 0;
                $res->error_desc    = '';
                $res->data          = $data;
                return response()->json($res,200);
            } else {
                $res = new \stdClass();
                $res->error_code    = 1;
                $res->error_desc    = 'No Content';
                $res->data          = [];
                return response()->json($res,200);
            }
        } catch(\Execption $e) {
            $res = new \stdClass();
            $res->error_code    = 5;
            $res->error_desc    = 'Internal Server Error';
            $res->data          = $e->getMessage();
            return response()->json($res,200);
        }
    }

    public function createResponden(Request $request){
        $dataToken = $request->input('dataToken');

        $nama_responden     = $request->input('nama_responden');
        $username_responden = $request->input('username_responden');
        $password_responden = $request->input('password_responden');

        $kelompok           = $request->input('kelompok');        

        $obj = [];
        $obj['nama_responden']      = strtoupper($nama_responden);
        $obj['username_responden']  = $username_responden;
        $obj['password_responden']  = BcryptHelper::hash($password_responden);
        try {
            $data = RespondensModel::findOne(['username_responden'=>$username_responden]);
            if($data) {
                $res = new \stdClass();
                $res->error_code    = 4;
                $res->error_desc    = 'Username sudah digunakan';
                $res->data          = [];
                return response()->json($res,200);
            } else {
                $data2 = RespondensModel::create($obj);
                if($data2) {
                    $obj1 = [];
                    $obj1['responden_id']   = $data2->id;
                    $obj1['kelompok']       = $kelompok;

                    $data3 = DemografiModel::create($obj1);

                    if($data3) {
                        $res = new \stdClass();
                        $res->error_code    = 0;
                        $res->error_desc    = '';
                        $res->data          = array_merge((array) $data3, (array) $data2);
                        return response()->json($res,200);
                    } else {
                        RespondensModel::destroy(['id'=>$data->id],true);
                        $res = new \stdClass();
                        $res->error_code    = 5;
                        $res->error_desc    = 'Gagal menambahkan demografi';
                        $res->data          = [];
                        return response()->json($res,200);
                    }
                } else {
                    $res = new \stdClass();
                    $res->error_code    = 5;
                    $res->error_desc    = 'Gagal menambahkan responden';
                    $res->data          = [];
                    return response()->json($res,200);
                }
            }
        } catch(\Exception $e) {
            $res = new \stdClass();
            $res->error_code    = 5;
            $res->error_desc    = 'Internal Server Error';
            $res->data          = $e->getMessage();
            return response()->json($res,200);
        }
    }

    public function updateResponden(Request $request, $id){
        // DB::table('debug')->insert(['input1'=>json_encode($request->all()),'input2'=>json_encode($request->all())]);
        $dataToken = $request->input('dataToken');

        $nama_responden     = $request->input('nama_responden');
        $username_responden = $request->input('username_responden');
        $password_responden = $request->input('password_responden');

        $kelompok           = $request->input('kelompok');        

        $obj = [];
        $obj['nama_responden']      = strtoupper($nama_responden);
        $obj['username_responden']  = $username_responden;
        $obj['password_responden']  = BcryptHelper::hash($password_responden);
        try {
            $data = RespondensModel::findOne([
                ['username_responden','=',$username_responden],
                ['id','<>',$id]
            ]);
            if($data) {
                $res = new \stdClass();
                $res->error_code    = 4;
                $res->error_desc    = 'Username sudah digunakan';
                $res->data          = [];
                return response()->json($res,200);
            } else {
                $collection2 = RespondensModel::_update($obj,['id'=>$id]);
                $data2 = $collection2->toArray();
                if($data2) {
                    $obj1 = [];
                    $obj1['responden_id']   = $data2[0]->id;
                    $obj1['kelompok']       = $kelompok;

                    $collection3 = DemografiModel::_update($obj1,['responden_id'=>$id]);
                    $data3 = $collection3->toArray();

                    if($data3) {
                        $res = new \stdClass();
                        $res->error_code    = 0;
                        $res->error_desc    = '';
                        $res->data          = array_merge((array) $data3[0], (array) $data2[0]);
                        return response()->json($res,200);
                    } else {
                        $res = new \stdClass();
                        $res->error_code    = 5;
                        $res->error_desc    = 'Gagal mengubah demografi';
                        $res->data          = [];
                        return response()->json($res,500);
                    }
                } else {
                    $res = new \stdClass();
                    $res->error_code    = 4;
                    $res->error_desc    = 'ID tidak ditemukan';
                    $res->data          = [];
                    return response()->json($res,200);
                }
            }
        } catch(\Exception $e) {
            $res = new \stdClass();
            $res->error_code    = 5;
            $res->error_desc    = 'Internal Server Error';
            $res->data          = $e->getMessage();
            return response()->json($res,200);
        }
    }

    public function deleteResponden(Request $request, $id){
        $dataToken = $request->input('dataToken');
        try {
            $collection = RespondensModel::destroy(['id'=>$id]);
            $data = $collection->toArray();
            if($data) {
                $res = new \stdClass();
                $res->error_code    = 0;
                $res->error_desc    = '';
                $res->data          = $data;
                return response()->json($res,200);
            } else {
                $res = new \stdClass();
                $res->error_code    = 1;
                $res->error_desc    = 'No Action';
                $res->data          = $e;
                return response()->json($res,200);
            }
        } catch(\Execption $e) {
            $res = new \stdClass();
            $res->error_code    = 5;
            $res->error_desc    = 'Internal Server Error';
            $res->data          = $e->getMessage();
            return response()->json($res,200);
        }
    }
}
