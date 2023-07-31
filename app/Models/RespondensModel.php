<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

use \Ramsey\Uuid\Uuid;
use \Carbon\Carbon;

use Illuminate\Support\Facades\DB;

class RespondensModel extends Model implements AuthenticatableContract, AuthorizableContract{
    use Authenticatable, Authorizable, HasFactory;

    public static $_table = 'respondens';

    public static function findAll(){
        try {
            $data = DB::table(self::$_table)->whereNull('deleted_at')->orderBy('created_at','desc')->orderBy('id','desc')->get();
            return $data;
        } catch(\Exception $e) {
            return false;
        }
    }

    public static function findByPk($id){
        try {
            $data = DB::table(self::$_table)->whereNull('deleted_at')->find($id);
            return $data;
        } catch(\Exception $e) {
            return false;
        }
    }

    public static function findOne($where=[],$order=null){
        try {
            if($order) {
                $data = DB::table(self::$_table)->whereNull('deleted_at')->where($where)->orderByRaw(DB::raw($order))->first();
            } else {
                $data = DB::table(self::$_table)->whereNull('deleted_at')->where($where)->orderBy('created_at','asc')->orderBy('id','asc')->first();
            }
            return $data;
        } catch(\Exception $e) {
            return false;
        }
    }

    public static function find($columns='*', $where=[], $order=null, $limit=null){
        try {
            $data = DB::table(self::$_table)->selectRaw($columns)->whereNull('deleted_at')->where($where)->orderByRaw(DB::raw('created_at desc, id desc'.($order?', ':'').$order))->limit($limit)->get();
            return $data;
        } catch(\Exception $e) {
            return false;
        }
    }

    public static function create($obj){
        $id = Uuid::uuid4()->toString();
        $obj['id'] = $id;
        $obj['created_at'] = Carbon::now();
        $obj['updated_at'] = Carbon::now();
        try {
            DB::table(self::$_table)->insert($obj);
            $data = DB::table(self::$_table)->find($id);
            return $data;
        } catch(\Exception $e) {
            return false;
        }
    }

    public static function bulkCreate($arrObj){
        $mod = array_map(function ($obj) {
            $id = Uuid::uuid1()->toString();
            $obj['id'] = $id;
            $obj['created_at'] = Carbon::now();
            $obj['updated_at'] = Carbon::now();

            return $obj;
        }, $arrObj);
        try {
            DB::table(self::$_table)->insert($mod);
            
            $arrId = array_reduce($mod, function ($total,$obj) {
                array_push($total, $obj['id']);
                return $total;
            },[]);
            $data = DB::table(self::$_table)->whereNull('deleted_at')->whereIn('id', $arrId)->get();

            return $data;
        } catch(\Exception $e) {
            return false;
        }
    }

    public static function _update($obj,$where){
        $obj['updated_at'] = Carbon::now();
        try {
            $arrId = DB::table(self::$_table)->where($where)->pluck('id');
            DB::table(self::$_table)->where($where)->update($obj);

            $data = DB::table(self::$_table)->whereNull('deleted_at')->whereIn('id', $arrId)->get();
            return $data;
        } catch(\Exception $e) {
            return false;
        }
    }

    public static function destroy($where,$force=false){
        try {
            if($force){
                $data = DB::table(self::$_table)->where($where)->get();
                DB::table(self::$_table)->where($where)->delete();
            } else {
                $arrId = DB::table(self::$_table)->whereNull('deleted_at')->where($where)->pluck('id');
                DB::table(self::$_table)->whereNull('deleted_at')->where($where)->update(['deleted_at'=>Carbon::now()]);

                $data = DB::table(self::$_table)->whereIn('id', $arrId)->get();
            }
            return $data;
        } catch(\Exception $e) {
            return false;
        }
    }
}
