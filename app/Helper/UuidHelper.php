<?php

namespace App\Helper;

use \Ramsey\Uuid\Uuid;

class UuidHelper{

    public static function v1(){
        return Uuid::uuid1()->toString();
    }

    public static function v4(){
        return Uuid::uuid4()->toString();
    }
}