<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    
    public function register()
    {
        $this->app->singleton(
            'mailer',
            function ($app) {
                return $app->loadComponent('mail', 'Illuminate\Mail\MailServiceProvider', 'mailer');
            }
        );
        $this->app->singleton(\Illuminate\Contracts\Routing\ResponseFactory::class, function() {
            return new \Laravel\Lumen\Http\ResponseFactory();
        });
        
        // Aliases
        $this->app->alias('mailer', \Illuminate\Contracts\Mail\Mailer::class);
        // Make Queue
        $this->app->make('queue');


        Validator::extend('without_spaces', function($attr, $value){
            return preg_match('/^\S*$/u', $value);
        });

        Validator::extend('latitude', function($attr, $value){
            return preg_match('/^((\-?|\+?)?\d+(\.\d+)?)$/', $value);
        });
        
        Validator::extend('longitude', function($attr, $value){
            return preg_match('/^((\-?|\+?)?\d+(\.\d+)?)$/', $value);
        });
        
    }
}
