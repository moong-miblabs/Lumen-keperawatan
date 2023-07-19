<?php

namespace App\Helper;

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class JsonwebtokenHelper{

    public static function sign($assocArr){
        $key = config('app.key');
        // JWT::$leeway = 60; // $leeway in seconds
        return JWT::encode($assocArr, $key, 'HS256');
    }

    public static function verify($token){
        $key = config('app.key');
        try {
            $decode = JWT::decode($token, new Key($key, 'HS256'));
            return $decode;
        } catch(\Exception $e) {
            return false;
        }
    }
}