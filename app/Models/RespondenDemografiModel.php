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

class RespondenDemografiModel extends Model implements AuthenticatableContract, AuthorizableContract{
    use Authenticatable, Authorizable, HasFactory;

    public static $_table = "(
        SELECT
            respondens.id AS id, respondens.nama_responden AS nama_responden, respondens.username_responden AS username_responden, respondens.password_responden AS password_repponden, respondens.created_at AS created_at, respondens.updated_at AS updated_at, respondens.deleted_at AS deleted_at,
            demografi.kelompok AS kelompok
        FROM
            respondens
        LEFT JOIN
            demografi
            ON
                respondens.id = demografi.responden_id
    ) AS tabel";

    public static function findAll(){
        try {
            $data = DB::table(DB::raw(self::$_table))->whereNull('deleted_at')->orderBy('created_at','desc')->orderBy('id','asc')->get();
            return $data;
        } catch(\Exception $e) {
            return false;
        }
    }

    public static function findByPk($id){
        try {
            $data = DB::table(DB::raw(self::$_table))->whereNull('deleted_at')->find($id);
            return $data;
        } catch(\Exception $e) {
            return false;
        }
    }

    public static function findOne($where=[],$order=null){
        try {
            if($order) {
                $data = DB::table(DB::raw(self::$_table))->whereNull('deleted_at')->where($where)->orderByRaw(DB::raw($order))->first();
            } else {
                $data = DB::table(DB::raw(self::$_table))->whereNull('deleted_at')->where($where)->orderBy('created_at','asc')->orderBy('id','asc')->first();
            }
            return $data;
        } catch(\Exception $e) {
            return false;
        }
    }

    public static function find($columns='*', $where=[], $order=null, $limit=null){
        try {
            $data = DB::table(DB::raw(self::$_table))->selectRaw($columns)->whereNull('deleted_at')->where($where)->orderByRaw(DB::raw('created_at desc, id asc'.($order?', ':'').$order))->limit($limit)->get();
            return $data;
        } catch(\Exception $e) {
            throw $e;
        }
    }
}
