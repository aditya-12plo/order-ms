<?php

$router->group([
    'middleware' => 'lang.auth'
], function ($router) {

    $router->get('/',['as' => 'index','uses' => 'IndexController@index']);

    $router->group(['prefix' => 'root-system'], function () use ($router) {
        
        $router->get('/version', function () use ($router) {
            return $router->app->version();
        });
        $router->get('/generate-pdf',['as' => 'indexpdf','uses' => 'RootSystemController@generatePdf']);
        $router->get('/pdf-encode',['as' => 'indexpdfEncode','uses' => 'RootSystemController@pdfencode']);
        $router->get('/download-xlsx',['as' => 'indexXlsx','uses' => 'RootSystemController@downloadExcel']);
        
    
    });

    $router->group(['prefix' => 'auth'], function () use ($router) {
        
        $router->post('/password/email',['as' => 'userResetPassword','uses' => 'AuthController@resetPassword']);        
        $router->post('/login',['as' => 'userLogin','uses' => 'AuthController@authenticate']);        
    
    });


    $router->group(
        ['middleware' => 'jwt.auth'], 
        function() use ($router) {

            $router->group(['prefix' => 'user'], function () use ($router) {
	
                $router->get('/permission-role',['as' => 'roleUser','uses' => 'UserController@roleUser']);
                $router->get('/access/detail',['as' => 'detailUserAccess','uses' => 'UserController@detailUserAccess']);
                
            });


    });

});