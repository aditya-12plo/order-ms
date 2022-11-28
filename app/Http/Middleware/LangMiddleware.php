<?php
namespace App\Http\Middleware;
use Closure;

class LangMiddleware
{
    public function handle($request, Closure $next)
    { 
		$localization 	= @$request->header('Accept-Language');
		if(@$localization == "en"){
            app('translator')->setLocale("en");
		}else{
			app('translator')->setLocale("id");
        }
        return $next($request);
 
    }
	 
}