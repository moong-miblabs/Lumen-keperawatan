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

    public static function usia($tanggal_lahir,$tanggal_saat_itu=null){
        date_default_timezone_set("Asia/Jakarta");
        $thn_lhr = date('Y',strtotime($tanggal_lahir));
        $bln_lhr = date('n',strtotime($tanggal_lahir));
        $tgl_lhr = date('j',strtotime($tanggal_lahir));

        if($tanggal_saat_itu) {
            $thn_itu = date('Y',strtotime($tanggal_saat_itu));
            $bln_itu = date('n',strtotime($tanggal_saat_itu));
            $tgl_itu = date('j',strtotime($tanggal_saat_itu));
        } else {
            $thn_itu = date('Y');
            $bln_itu = date('n');
            $tgl_itu = date('j');
        }

        if($tgl_itu - $tgl_lhr < 0) {
            $tgl_itu += 30;
            $bln_itu --;
        }

        if($bln_itu - $bln_lhr < 0){
            $bln_itu += 12;
            $thn_itu --;
        }

        return [
            'tahun' => $thn_itu-$thn_lhr,
            'bulan' => $bln_itu-$bln_lhr,
            'hari'  => $tgl_itu-$tgl_lhr
        ];
    }
}