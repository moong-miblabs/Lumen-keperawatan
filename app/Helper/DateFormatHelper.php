<?php

namespace App\Helper;

class DateFormatHelper{

    public static $arr_hari = [1=>'Senin','Selasa','Rabu','Kamis','Jum\'at','Sabtu','Minggu'];
    public static $arr_bulan = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    public static $arr_bln = [1=>'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

    public static function file($d=null){
        date_default_timezone_set("Asia/Jakarta");
        if($d) {
            return date('YmdHis',strtotime($d));
        } else {
            return date('YmdHis');
        }
    }

    public static function human($d=null){
        date_default_timezone_set("Asia/Jakarta");
        if($d) {
            $num_hari = date('N',strtotime($d));
        } else {
            $num_hari = date('N');
        }
        $hari = self::$arr_hari[$num_hari];

        if($d) {
            $num_bulan = date('n',strtotime($d));
        } else {
            $num_bulan = date('n');
        }
        $bulan = self::$arr_bulan[$num_bulan];

        if($d) {
            $tgl = date('j',strtotime($d));
        } else {
            $tgl = date('j');
        }
        
        if($d) {
            return $hari . ", " . $tgl . " " . $bulan . " " . date('Y H:i',strtotime($d));
        } else {
            return $hari . ", " . $tgl . " " . $bulan . " " . date('Y H:i');
        }
    }

    public static function humanDate($d=null){
        date_default_timezone_set("Asia/Jakarta");
        if($d) {
            $num_hari = date('N',strtotime($d));
        } else {
            $num_hari = date('N');
        }
        $hari = self::$arr_hari[$num_hari];

        if($d) {
            $num_bulan = date('n',strtotime($d));
        } else {
            $num_bulan = date('n');
        }
        $bulan = self::$arr_bulan[$num_bulan];

        if($d) {
            $tgl = date('j',strtotime($d));
        } else {
            $tgl = date('j');
        }
        
        if($d) {
            return $hari . ", " . $tgl . " " . $bulan . " " . date('Y',strtotime($d));
        } else {
            return $hari . ", " . $tgl . " " . $bulan . " " . date('Y');
        }
    }

    public static function dateGT($tanggal){
        date_default_timezone_set("Asia/Jakarta");
        $now        = date('Ymd');
        $compare    = date('Ymd',strtotime($tanggal));

        if($now>$compare) {
            return true;
        } else {
            return false;
        }
    }

    public static function dateLT($tanggal){
        date_default_timezone_set("Asia/Jakarta");
        $now        = date('Ymd');
        $compare    = date('Ymd',strtotime($tanggal));

        if($now<$compare) {
            return true;
        } else {
            return false;
        }
    }

    public static function dateGTE($tanggal){
        date_default_timezone_set("Asia/Jakarta");
        $now        = date('Ymd');
        $compare    = date('Ymd',strtotime($tanggal));

        if($now>=$compare) {
            return true;
        } else {
            return false;
        }
    }

    public static function dateLTE($tanggal){
        date_default_timezone_set("Asia/Jakarta");
        $now        = date('Ymd');
        $compare    = date('Ymd',strtotime($tanggal));

        if($now<=$compare) {
            return true;
        } else {
            return false;
        }
    }
}