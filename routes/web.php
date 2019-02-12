<?php

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
    return $router->app->version();
});

$router->post('/auth/login', 'AuthController@handle');
$router->post('/password/email', 'AuthController@sendPasswordResetEmail');
$router->post('/password/reset/{token}', 'AuthController@changePassword');

$router->group(['prefix'=> '/api/v1', 'middleware' => 'auth:api'], function($app) {
    $app->get('tasks', 'TaskController@index');
    $app->post('tasks', 'TaskController@create');
    $app->get('tasks/{id}', 'TaskController@view');
    $app->put('tasks/{id}', 'TaskController@update');
    $app->delete('tasks/{id}', 'TaskController@delete');

    $app->get('me/tasks', 'TodoController@userTasks');
    $app->get('users/{id}/tasks', 'TodoController@viewByUser');

    $app->get('logs', 'LogsController@index');
});