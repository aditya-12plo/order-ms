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

$router->get('/',['as' => 'index','uses' => 'IndexController@index']);

$router->group(['prefix' => 'root-system'], function () use ($router) {
	
    $router->get('/version', function () use ($router) {
        return $router->app->version();
    });
    $router->get('/generate-pdf',['as' => 'indexpdf','uses' => 'RootSystemController@generatePdf']);
    $router->get('/pdf-encode',['as' => 'indexpdfEncode','uses' => 'RootSystemController@pdfencode']);
    

});