<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\UsersModel;

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
            $res->data          = $e;
            return response()->json($res,500);
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
            return new Response(false,200);
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

    public function listResponden(Request $request){
        $dataToken = $request->input('dataToken');
        try {
            $data = RespondenDemografiModel::findAll();
            if($data) {
                $res = new \stdClass();
                $res->error_code    = 0;
                $res->error_desc    = '';
                $res->data          = $data;
                return response()->json($res,200);
            } else {
                $res = new \stdClass();
                $res->error_code    = 5;
                $res->error_desc    = 'Internal Server Error';
                $res->data          = $e;
                return response()->json($res,500);
            }
        } catch(\Execption $e) {
            $res = new \stdClass();
            $res->error_code    = 5;
            $res->error_desc    = 'Internal Server Error';
            $res->data          = $e;
            return response()->json($res,500);
        }
    }

    public function detailResponden(Request $request, $id){
        $dataToken = $request->input('dataToken');
        try {
            $data = RespondenDemografiModel::findOne(['id'=>$id]);
            if($data) {
                $data->gender = $data->gender=='Laki-laki'?'L':'P';
                $res = new \stdClass();
                $res->error_code    = 0;
                $res->error_desc    = '';
                $res->data          = $data;
                return response()->json($res,200);
            } else {
                $res = new \stdClass();
                $res->error_code    = 5;
                $res->error_desc    = 'Internal Server Error';
                $res->data          = [];
                return response()->json($res,500);
            }
        } catch(\Execption $e) {
            $res = new \stdClass();
            $res->error_code    = 5;
            $res->error_desc    = 'Internal Server Error';
            $res->data          = $e;
            return response()->json($res,500);
        }
    }

    public function createResponden(Request $request){
        $dataToken = $request->input('dataToken');

        $nama_responden     = $request->input('nama_responden');
        $username_responden = $request->input('nama_responden');
        $password_responden = $request->input('nama_responden');

        $umur               = $request->input('umur');
        $gender             = $request->input('gender');
        $kelurahan          = $request->input('kelurahan');
        $rt                 = $request->input('rt');
        $rw                 = $request->input('rw');
        $alamat             = $request->input('alamat');
        $anggota_keluarga   = $request->input('anggota_keluarga');
        $luas_rumah         = $request->input('luas_rumah');
        $pendidikan         = $request->input('pendidikan');
        $pekerjaan          = $request->input('pekerjaan');

        $obj = [];
        $obj['nama_responden']      = $nama_responden;
        $obj['username_responden']  = $username_responden;
        $obj['password_responden']  = BcryptHelper::hash($password_responden);
        try {
            $data = RespondensModel::findOne(['username_responden'=>$username_responden]);
            if($data) {
                return new Response('gagal1',200);
            } else {
                $data2 = RespondensModel::create($obj);
                if($data2) {
                    $obj1 = [];
                    $obj1['responden_id']   = $data2->id;
                    $obj1['kelompok']       = 'II';
                    $obj1['umur']           = $umur;
                    $obj1['gender']         = $gender;
                    $obj1['kelurahan']      = $kelurahan;
                    $obj1['rt']             = $rt;
                    $obj1['rw']             = $rw;
                    $obj1['alamat']         = $alamat;
                    $obj1['anggota_keluarga'] = $anggota_keluarga;
                    $obj1['luas_rumah']     = $luas_rumah;
                    $obj1['pendidikan']     = $pendidikan;
                    $obj1['pekerjaan']      = $pekerjaan;

                    $data3 = DemografiModel::create($obj1);

                    if($data3) {
                        return new Response('New record created successfully',200);
                    } else {
                        RespondensModel::destroy(['id'=>$data->id],true);
                        return new Response('Error',200);
                    }
                } else {
                    return new Response('Error',200);
                }
            }
        } catch(\Exception $e) {
            return new Response('Error',200);
        }
    }

    public function updateResponden(Request $request, $id){
        // DB::table('debug')->insert(['input1'=>json_encode($request->all()),'input2'=>json_encode($request->all())]);
        $dataToken = $request->input('dataToken');

        $nama_responden     = $request->input('nama_responden');
        $username_responden = $request->input('username_responden');
        $password_responden = BcryptHelper::hash($request->input('password_responden'));

        $umur               = $request->input('umur');
        $gender             = $request->input('gender');
        $kelurahan          = $request->input('kelurahan');
        $rt                 = $request->input('rt');
        $rw                 = $request->input('rw');
        $alamat             = $request->input('alamat');
        $anggota_keluarga   = $request->input('anggota_keluarga');
        $luas_rumah         = $request->input('luas_rumah');
        $pendidikan         = $request->input('pendidikan');
        $pekerjaan          = $request->input('pekerjaan');

        $obj = [];
        $obj['nama_responden']      = $nama_responden;
        $obj['username_responden']  = $username_responden;
        $obj['password_responden']  = BcryptHelper::hash($password_responden);

        $obj1 = [];
        $obj1['kelompok']       = 'II';
        $obj1['umur']           = $umur;
        $obj1['gender']         = $gender;
        $obj1['kelurahan']      = $kelurahan;
        $obj1['rt']             = $rt;
        $obj1['rw']             = $rw;
        $obj1['alamat']         = $alamat;
        $obj1['anggota_keluarga']= $anggota_keluarga;
        $obj1['luas_rumah']     = $luas_rumah;
        $obj1['pendidikan']     = $pendidikan;
        $obj1['pekerjaan']      = $pekerjaan;
        try {
            $data = RespondensModel::_update($obj,['id'=>$id]);
            $data1 = DemografiModel::_update($obj1,['responden_id'=>$id]);

            $res = new \stdClass();
            $res->error_code    = 0;
            $res->error_desc    = '';
            $res->data          = array_merge((array) $data1[0], (array) $data[0]);
            return response()->json($res,200);
        } catch(\Exception $e) {
            // return $e;
            $res = new \stdClass();
            $res->error_code    = 5;
            $res->error_desc    = 'Internal Server Error';
            $res->data          = $e;
            return response()->json($res,500);
        }
    }

    public function deleteResponden(Request $request, $id){
        $dataToken = $request->input('dataToken');
        try {
            $data = RespondensModel::destroy(['id'=>$id]);
            if($data) {
                $res = new \stdClass();
                $res->error_code    = 0;
                $res->error_desc    = '';
                $res->data          = $data;
                return response()->json($res,200);
            } else {
                $res = new \stdClass();
                $res->error_code    = 5;
                $res->error_desc    = 'Internal Server Error';
                $res->data          = $e;
                return response()->json($res,500);
            }
        } catch(\Execption $e) {
            $res = new \stdClass();
            $res->error_code    = 5;
            $res->error_desc    = 'Internal Server Error';
            $res->data          = $e;
            return response()->json($res,500);
        }
    }
}
