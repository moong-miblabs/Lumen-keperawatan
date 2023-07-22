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
        'middleware'    => 'apiGet',
        'uses'          => 'Web@testMiddleware'
    ]);
    $router->get('list-prepost', [
        'middleware'    => 'apiGet',
        'uses'          => 'Web@testMiddleware'
    ]);
    $router->get('detail-prepost/{id}', [
        'middleware'    => 'apiPost',
        'uses'          => 'Web@testMiddleware'
    ]);
    $router->post('update-prepost/{id}', [
        'middleware'    => 'apiGet',
        'uses'          => 'Web@testMiddleware'
    ]);
    $router->get('delete-prepost/{id}', [
        'middleware'    => 'apiGet',
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

$router->post('create-responden', [
    'middleware'    => 'apiGet',
    'uses'          => 'Main@testMiddleware'
]);
$router->get('list-responden', [
    'middleware'    => 'apiGet',
    'uses'          => 'Main@testMiddleware'
]);
$router->get('detail-responden/{id}', [
    'middleware'    => 'apiPost',
    'uses'          => 'Main@testMiddleware'
]);
$router->post('update-responden/{id}', [
    'middleware'    => 'apiGet',
    'uses'          => 'Main@testMiddleware'
]);
$router->get('delete-responden/{id}', [
    'middleware'    => 'apiGet',
    'uses'          => 'Main@testMiddleware'
]);