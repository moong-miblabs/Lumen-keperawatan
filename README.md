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

## Setup - 1

1. Setup .env
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
	            foreach($headers as $key => $value){
	                $response->header($key, $value);
	            }

	            return $response;
	        }
	    }
	}
	```
	2. register Cors Middleware as global middleware in bootstrap\app.php
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

## Setup - 2

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
