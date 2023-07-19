<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return 'Back-End Keperawatan by Lumen Laravel';
});

$router->group(['prefix' => 'setup'], function () use ($router) {
    $router->get('dbsync', [
        'uses' => 'Setup@dbsync'
    ]);
    $router->get('seed', [
        'uses' => 'Setup@seed'
    ]);
    $router->get('drop', [
        'uses' => 'Setup@drop'
    ]);
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('login', [
        'uses' => 'Api@login'
    ]);
    $router->get('middleware', [
        'middleware'    => 'apiGet',
        'uses'          => 'Api@testMiddleware'
    ]);
    $router->post('middleware', [
        'middleware'    => 'apiPost',
        'uses'          => 'Api@testMiddleware'
    ]);
});