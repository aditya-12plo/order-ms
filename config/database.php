<?php

return [

   'default' => 'mysql',
   'fetch' => PDO::FETCH_CLASS,
   'connections' => [
        'mysql' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST'),
            'port'      => env('DB_PORT'),
            'database'  => env('DB_DATABASE'),
            'username'  => env('DB_USERNAME'),
            'password'  => env('DB_PASSWORD'),
            'charset'   =>  env('DB_CHARSET'),
            'collation' =>  env('DB_COLLATION'),
            'prefix'    => '',
            'strict'    => false,
            'options'  => array(
                            // PDO::MYSQL_ATTR_SSL_CA => "/var/ssl/azzure.pem"
                        )
         ],
        'mongodb' => [
             'driver'    => 'mongodb',
             'host'      => env('DB2_HOST'),
             'port'      => env('DB2_PORT'),
             'database'  => env('DB2_DATABASE'),
             'username'  => env('DB2_USERNAME'),
             'password'  => env('DB2_PASSWORD'),
             'options'  => array(
                'database' => env('DB2_AUTHENTICATION_DATABASE'),
                         )
         ],
    ],
];