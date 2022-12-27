<?php
namespace App\Http\Middleware;
use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;

use App\Models\User;
use App\Models\View\VUserRoles;
use App\Models\CompanyGroup;


class JwtMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        $token 					= $request->bearerToken();
        $refreshToken			= $this->bearerRefreshToken($request);
        $request->credentials	= false;

        if(!$token) {
            // Unauthorized response if token not there
			
			$message = trans("translate.tokenprovided");
			return response()
				->json(['status'=>401 ,'datas' => null, 'errors' => ['message' => [$message]]])
				->withHeaders([
				  'Content-Type'          => 'application/json',
				  ])
				->setStatusCode(401);
        }
        try {
            $credentials = JWT::decode($token,  new Key(env('APP_KEY'), 'HS256'));
        } catch(ExpiredException $e) {
			
			if($e->getMessage() == "Expired token"){
				
				if($refreshToken){
					$getUser	= User::with(['company_detail','role'])->where([["remember_token",$refreshToken],["status","ACTIVATE"]])->first();
					
					if($getUser){

						$getTotalLastLogin 				= $this->getTotalLastLogin($getUser->updated_at);
						if($getTotalLastLogin < 30){

							$getNewToken			= $this->refreshToken($getUser);
							
							$request->auth			= $getUser;
							$request->credentials	= ["access_token" => $getNewToken, "refresh_token" => $refreshToken];
						
							return $next($request);

						}else{
							
							$message = trans("translate.ProvidedTokenexpired");
							return response()
								->json(['status'=>401 ,'datas' => null, 'errors' => ['message' => [$message]]])
								->withHeaders([
								'Content-Type'          => 'application/json',
								])
								->setStatusCode(401);

						}

					}else{

						$message = trans("translate.ProvidedTokenexpired");
						return response()
							->json(['status'=>401 ,'datas' => null, 'errors' => ['message' => [$message]]])
							->withHeaders([
							  'Content-Type'          => 'application/json',
							  ])
							->setStatusCode(401);

					}
 
				}else{

					$message = trans("translate.ProvidedTokenexpired");
					return response()
						->json(['status'=>401 ,'datas' => null, 'errors' => ['message' => [$message]]])
						->withHeaders([
						  'Content-Type'          => 'application/json',
						  ])
						->setStatusCode(401);
				}

			}else{

				$message = trans("translate.ProvidedTokenexpired");
				return response()
					->json(['status'=>401 ,'datas' => null, 'errors' => ['message' => [$message]]])
					->withHeaders([
					  'Content-Type'          => 'application/json',
					  ])
					->setStatusCode(401);

			}

        } catch(Exception $e) {
			
			$message = trans("translate.tokendecoding");

			return response()
				->json(['status'=>400 ,'datas' => null, 'errors' => ['message' => [$message]]])
				->withHeaders([
				  'Content-Type'          => 'application/json',
				  ])
				->setStatusCode(400);
        }
		
		$permision_role				= VUserRoles::where("id_role",$credentials->sub->id_role)->orderBy("permission.sequence","ASC")->get();
        $request->permision_role 	= $permision_role;
        $request->auth 				= $credentials->sub;
        $request->company_group		= CompanyGroup::with(["company_detail"])->where("id_company_group",$credentials->sub->id_company)->get();
        return $next($request);
		
    }
	
	public function bearerToken(){
		$header	= $this->header('Authorization','');
		if(Str::startsWith($header, 'Bearer ')){
			return Str::substr($header, 7);
		}
	}

	
	private function bearerRefreshToken($request){
		$header = explode(':', $request->header('Refresh-Token'));
		$refreshToken = @trim($header[0]);
		return $refreshToken;
	}


	
	
	private function getTotalLastLogin($date1){
		$date2 = date("Y-m-d H:i:s");

		$diff = abs(strtotime($date2) - strtotime($date1));

		$years 	= floor($diff / (365*60*60*24));
		$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
		$days 	= floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

		return $days;
	}

	
	private function refreshToken($user){
		User::where("id_user",$user->id_user)->update([
			"updated_at"    => date("Y-m-d H:i:s")
		]);
 
		$payload = [
			'iss' => "bearer",
			'sub' => $user,
			'iat' => time(),
			'exp' => time() + 1440*60 // token kadaluwarsa setelah 3600 detik
		];
		
		return JWT::encode($payload, env('APP_KEY'), 'HS256');
	}

}