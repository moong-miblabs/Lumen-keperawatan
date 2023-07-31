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

$router->group(['prefix' => 'web'], function () use ($router) {
    $router->post('register', [
        'uses' => 'Web@login'
    ]);
    $router->post('login', [
        'uses' => 'Web@login'
    ]);
    $router->post('verify', [
        'uses' => 'Web@verify'
    ]);
    $router->post('create-prepost', [
        'middleware'    => 'auth',
        'uses'          => 'Web@testMiddleware'
    ]);
    $router->get('list-prepost', [
        'middleware'    => 'auth',
        'uses'          => 'Web@testMiddleware'
    ]);
    $router->get('detail-prepost/{id}', [
        'middleware'    => 'auth',
        'uses'          => 'Web@testMiddleware'
    ]);
    $router->post('update-prepost/{id}', [
        'middleware'    => 'auth',
        'uses'          => 'Web@testMiddleware'
    ]);
    $router->get('delete-prepost/{id}', [
        'middleware'    => 'auth',
        'uses'          => 'Web@testMiddleware'
    ]);
});

$router->post('login',[
    'uses' => 'Main@login'
]);

$router->post('verify',[
    'uses' => 'Main@verify'
]);

$router->get('home',[
    'middleware'    => 'auth',
    'uses'          => 'Main@home'
]);

$router->get('list-responden', [
    'middleware'    => 'auth',
    'uses'          => 'Main@listResponden'
]);
$router->get('detail-responden/{id}', [
    'middleware'    => 'auth',
    'uses'          => 'Main@detailResponden'
]);
$router->post('create-responden', [
    'middleware'    => 'auth',
    'uses'          => 'Main@createResponden'
]);
$router->post('update-responden/{id}', [
    'middleware'    => 'auth',
    'uses'          => 'Main@updateResponden'
]);
$router->get('delete-responden/{id}', [
    'middleware'    => 'auth',
    'uses'          => 'Main@deleteResponden'
]);