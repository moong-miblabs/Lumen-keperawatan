# Lumen PHP Framework

[![Build Status](https://travis-ci.org/laravel/lumen-framework.svg)](https://travis-ci.org/laravel/lumen-framework)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel/lumen-framework)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Stable Version](https://img.shields.io/packagist/v/laravel/lumen-framework)](https://packagist.org/packages/laravel/lumen-framework)
[![License](https://img.shields.io/packagist/l/laravel/lumen)](https://packagist.org/packages/laravel/lumen-framework)

Laravel Lumen is a stunningly fast PHP micro-framework for building web applications with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Lumen attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as routing, database abstraction, queueing, and caching.

## Official Documentation

Documentation for the framework can be found on the [Lumen website](https://lumen.laravel.com/docs).

## Contributing

Thank you for considering contributing to Lumen! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Lumen, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

## License

The Lumen framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## List of Laravel Database response

|Query Type|return value|
|--|--|
|DB::table()->get()|Laravel collection|
|DB::table()->find()|ArrayObject Class|
|DB::table()->first()|ArrayObject Class|
|DB::table()->pluck()|Array|
|DB::select()|Array of ArrayObject Class|

>```
> Laravel Collection always return ***TRUE***
> Empty Array return ***FALSE***
> Use `toArray()` for convert Laravel Collection to Array of Associative Array
> Use `isEmpty()` for check Laravel Collection is empty. return ***TRUE*** if empty 
>```

## Setup - 1 : config, cors and prepare for model requirement

1. Setup .env (DB and APP_KEY must be fill)
2. Copy index.php and .htaccess from public, paste to ROOT. in index.php, replace path to bootstrap/app.php
	```php
	$app = require __DIR__.'/../bootstrap/app.php';
	```
	to
	```php
	$app = require __DIR__.'/bootstrap/app.php';
	```
3. CorsMiddleware
	1. Create file CorsMiddleware.php in app/Http/Middleware/CorsMiddleware.php
	```php
	<?php

    namespace App\Http\Middleware;

    use Closure;

    class CorsMiddleware{
        public function handle($request, Closure $next){
            $origin = '*';
            // $origin = $request->server->get('HTTP_ORIGIN');

            $allowedOrigins = [
                NULL,
                '',
                ''
            ];

            if(in_array($origin, $allowedOrigins) or $origin =='*'){    
                $headers = [
                    'Access-Control-Allow-Origin'      => $origin,
                    'Access-Control-Allow-Methods'     => 'POST, GET, OPTIONS, PUT, DELETE',
                    'Access-Control-Allow-Credentials' => 'true',
                    'Access-Control-Max-Age'           => '86400',
                    'Access-Control-Allow-Headers'     => 'Content-Type, Authorization, X-Requested-With'
                ];

                if ($request->isMethod('OPTIONS')){
                    return response()->json('{"method":"OPTIONS"}', 200, $headers);
                }

                $response = $next($request);
                $IlluminateResponse = 'Illuminate\Http\Response';
                $SymfonyResopnse = 'Symfony\Component\HttpFoundation\Response';
                if($response instanceof $IlluminateResponse) {
                    foreach ($headers as $key => $value) {
                        $response->header($key, $value);
                    }
                    return $response;
                }

                if($response instanceof $SymfonyResopnse) {
                    foreach ($headers as $key => $value) {
                        $response->headers->set($key, $value);
                    }
                    return $response;
                }
            }
        }
    }
	```
	2. register Cors Middleware as global middleware in bootstrap/app.php
	```php
	/*
	|--------------------------------------------------------------------------
	| Register Middleware
	|--------------------------------------------------------------------------
	|
	| Next, we will register the middleware with the application. These can
	| be global middleware that run before and after each request into a
	| route or middleware that'll be assigned to some specific routes.
	|
	*/

	$app->middleware([
	    // App\Http\Middleware\ExampleMiddleware::class
	    App\Http\Middleware\CorsMiddleware::class
	]);

	// $app->routeMiddleware([
	//     'auth' => App\Http\Middleware\Authenticate::class,
	// ]);
	```
4. Install ramsey/uuid, via `composer require ramsey/uuid`
5. Install nesbot/carbon, via `composer require nesbot/carbon`
6. (Optional) create Uuid helper
    1. create directory `Helper` in `app` (if not exists)
    2. create file `BcryptHelper.php` in `app/Helper/BcryptHelper.php`
    ```php
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
    ```

## Setup - 2 : dbsync, seed, drop

1. Enable facades : uncomment `$app->withFacades();` in bootstrap/app.php
2. Router Group : prefix => setup
	1. **GET** dbsync
	2. **GET** seed
	3. **GET** drop
3. Controller : Setup
	1. dbsync
	2. seed
	3. drop
3. Model : SetupModel
	1. dbsync
	2. seed
	3. drop

## Setup - 3 : create Bcrypt Helper

1. create bcrypt helper
2. create directory `Helper` in `app` (if not exists)
3. create file `BcryptHelper.php` in `app/Helper/BcryptHelper.php`
```php
<?php

namespace App\Helper;

class BcryptHelper{

    public static function hash($str){
        return password_hash($str, PASSWORD_BCRYPT, ["cost" => 10]);
    }

    public static function compare($password,$hash){
        return password_verify($password,$hash);
    }
}
```

## Setup - 4 : create Jsonwebtoken Helper

1. create jsonwebtoken helper
2. run composer `composer require firebase/php-jwt`
3. create directory `Helper` in `app` (if not exists)
4. create file `JsonwebtokenHelper.php` in `app/Helper/JsonwebtokenHelper.php`
```php
<?php

namespace App\Helper;

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class JsonwebtokenHelper{

    public static function sign($assocArr){
        $key = config('app.key');
        $assocArr['iat'] = strtotime("now");
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
```

## Setup - 5 : create middlewares (apiget, apipost, auth)

1. create file `ApiGetMiddleware.php` in `app/Http/Middleware/ApiGetMiddleware.php`
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

use App\Models\RespondensModel;

class ApiGetMiddleware{
    public function handle($request, Closure $next){
        $username = $request->query('id');
        try {
            $data = RespondensModel::findOne(['username_responden'=>$username]);
            if($data){
                $request->merge(['dataResponden' => (array) $data]);
                return $next($request);
            } else {
                $res = new \stdClass();
                $res->error_code = 4;
                $res->error_desc = 'Unauthorized';
                $res->data = [];
                return response()->json($res,200);
            }
        } catch(\Exception $e) {
            $res = new \stdClass();
            $res->error_code = 5;
            $res->error_desc = 'Internal Server Error';
            $res->data = $e;
            return response()->json($res,200);
        }
    }
}

```
2. create file `ApiPostMiddleware.php` in `app/Http/Middleware/ApiPostMiddleware.php`
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

use App\Models\RespondensModel;

class ApiPostMiddleware{
    public function handle($request, Closure $next){
        $username = $request->input('id');
        try {
            $data = RespondensModel::findOne(['username_responden'=>$username]);
            if($data){
                $request->merge(['dataResponden' => (array) $data]);
                return $next($request);
            } else {
                $res = new \stdClass();
                $res->error_code = 4;
                $res->error_desc = 'Unauthorized';
                $res->data = [];
                return response()->json($res,200);
            }
        } catch(\Exception $e) {
            $res = new \stdClass();
            $res->error_code = 5;
            $res->error_desc = 'Internal Server Error';
            $res->data = $e;
            return response()->json($res,200);
        }
    }
}

```
3. create file `AuthMiddleware.php` in `app/Http/Middleware/AuthMiddleware.php`
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

use App\Helper\JsonwebtokenHelper;

class AuthMiddleware{
    public function handle($request, Closure $next){
        $token = $request->header('Authorization');
        if(!$token) {
            if ($request->isMethod('post')) {
                $token = $request->input('token');
            } else {
                $token = $request->query('token');
            }
            if(!$token){
                $res = new \stdClass();
                $res->error_code = 4;
                $res->error_desc = 'Unauthorized';
                $res->data = [];
                return response()->json($res,200);
            }
        }
        try {
            $decoded = JsonwebtokenHelper::verify($token);
            if($decoded){
                $request->merge(['dataToken' => (array) $decoded]);
                return $next($request);
            } else {
                $res = new \stdClass();
                $res->error_code = 4;
                $res->error_desc = 'Unauthorized';
                $res->data = [];
                return response()->json($res,200);
            }
        } catch(\Exception $e) {
            return $e;
            $res = new \stdClass();
            $res->error_code = 5;
            $res->error_desc = 'Internal Server Error';
            $res->data = $e;
            return response()->json($res,200);
        }
    }
}
```

## Setup - 6 : upload file

1. install league/flysystem, via `composer require league/flysystem:^3.0`. Because Lumen dont have any storage, so we must install manually
2. (Optional) create DateFormatHelper.php in `app/Http/Middleware/DateFormatMiddleware.php`
```php
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
```
3. script Contoller for uploading like shown below
```php
use Illuminate\Support\Facades\Storage;
// if file sent as based64
// then hasFile return false
if($request->hasFile('foto')) {
    $file       = $request->file('foto');
    $extension  = $file->extension();

    $destination_path   = './public/foto/';
    $file_name          = DateFormatHelper::file() . '@'. $dataResponden['id'] . '.' . $extension;
    $file->move($destination_path,$file_name);
}
// if file sent as based64
// then input variabel (this case : foto) contain string 
else {
    // we need use Illuminate\Support\Facades\Storage;
    $file_name  = DateFormatHelper::file() . '@'. $dataResponden['id'] . '.jpg';
    $image      = base64_decode($request->input('foto'));
    
    $disk       = Storage::build([
        'driver'    => 'local',
        'root'      => 'public/foto',
    ]);
    $disk->put($file_name,$image);
}
// foto variabel for DB insert will set path of the file
$foto       = '/public/foto/'.$file_name;
```


## setup 7 : Download MS Excel (.xlsx)

1. Install Laravel Excel via composer `composer require maatwebsite/excel`
2. add the ServiceProvider in `bootstrap/app.php`
```php
    /*
    |--------------------------------------------------------------------------
    | Register Service Providers
    |--------------------------------------------------------------------------
    |
    | Here we will register all of the application's service providers which
    | are used to bind services into the container. Service providers are
    | totally optional, so you are not required to uncomment this line.
    |
    */

    $app->register(Maatwebsite\Excel\ExcelServiceProvider::class);
    // $app->register(App\Providers\AppServiceProvider::class);
    // $app->register(App\Providers\AuthServiceProvider::class);
    // $app->register(App\Providers\EventServiceProvider::class);
```
3. you can create file `RespondensExport` in `app/Exports`
```php
    <?php

    namespace App\Exports;

    use App\Models\RespondensModel;
    use Maatwebsite\Excel\Concerns\FromCollection;

    class RespondensExport implements FromCollection
    {
        public function collection()
        {
            return RespondensModel::findAll();
        }
    }
```
4. In your controller you can call this export now
```php
<?php

namespace App\Http\Controllers;

use App\Exports\RespondensExport;
use Maatwebsite\Excel\Facades\Excel;

class UsersController extends Controller 
{
    public function export() 
    {
        return Excel::download(new RespondensExport, 'respondens.xlsx');
    }
}
```

⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

# Authentication Respondens
## End-point: Login
### Method: POST
>```
>http://keperawatan.local/api/login
>```
### Body (**raw**) JSON

```json
{
    "username" : "a",
    "password" : "a"
}
```

⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

# Authentication Users
## End-point: Login
### Method: POST
>```
>http://keperawatan.local/login
>```
### Body (**raw**) JSON

```json
{
    "username" : "admin",
    "password" : "admin"
}
```


⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

## End-point: Verify Token
### Method: POST
>```
>http://keperawatan.local/verify
>```
### Body (**raw**) JSON

```json
{
    "token" : "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjAwMDAwMDAwLTAwMDAtMDAwMC0wMDAwLTAwMDAwMDBhZG1pbiJ9.gHe_N1W-Jbxephht3L_JeQAesg9XjLMH120mNPetU4s"
}
```


⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

## End-point: Home (Login Required, guarded by middleware auth)
### Method: GET
>```
>http://keperawatan.local/home
>```

### Headers

|Content-Type|Value|
|---|---|
|Authorization|eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjAwMDAwMDAwLTAwMDAtMDAwMC0wMDAwLTAwMDAwMDBhZG1pbiJ9.gHe_N1W-Jbxephht3L_JeQAesg9XjLMH120mNPetU4s|


⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

## End-point: List Respondens
### Method: GET
>```
>http://keperawatan.local/list-reponden
>```

### Headers

|Content-Type|Value|
|---|---|
|Authorization|eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjAwMDAwMDAwLTAwMDAtMDAwMC0wMDAwLTAwMDAwMDBhZG1pbiJ9.gHe_N1W-Jbxephht3L_JeQAesg9XjLMH120mNPetU4s|

⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

## End-point: Detail Respondens
### Method: GET
>```
>http://keperawatan.local/detail-reponden/{id}
>```

### Params

|Key|Value|
|---|---|
|id|00000000-0000-0000-0000-000responden|

### Headers

|Content-Type|Value|
|---|---|
|Authorization|eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjAwMDAwMDAwLTAwMDAtMDAwMC0wMDAwLTAwMDAwMDBhZG1pbiJ9.gHe_N1W-Jbxephht3L_JeQAesg9XjLMH120mNPetU4s|

⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

## End-point: Create Respondens (unique username guarded)
### Method: POST
>```
>http://keperawatan.local/create-responden
>```

### Body (**raw**) JSON

```json
{
    "nama_responden"    : "munji",
    "username_responden": "m",
    "password_responden": "m",

    "kelompok"          : "II"
}
```

### Headers

|Content-Type|Value|
|---|---|
|Authorization|eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjAwMDAwMDAwLTAwMDAtMDAwMC0wMDAwLTAwMDAwMDBhZG1pbiJ9.gHe_N1W-Jbxephht3L_JeQAesg9XjLMH120mNPetU4s|