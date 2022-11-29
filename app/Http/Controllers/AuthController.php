<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Mail;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use App\Mail\ForgotPasswordNotification;

use App\Models\User;


class AuthController extends Controller
{
    public function __construct()
    {
        
    }
    

    public function resetPassword(Request $request){ 
       
        $validator = Validator::make($request->all(), [
            'email'         => 'required|max:255|email', 
            'company_code'  => 'required|max:255'
        ]);
  
        if ($validator->fails()) {
            return response()
            ->json(['status'=>422 ,'datas' => null, 'errors' => $validator->errors()])
            ->withHeaders([
                'Content-Type'          => 'application/json',
            ])
            ->setStatusCode(422);
        }
  
        $company_code = $request->company_code;
        $check  = User::where([["email",$request->email],["status","ACTIVATE"]])->whereHas('company_detail',function($q) use ($company_code){
          return $q->where('code',$company_code);
        })->first();
        if($check){
  
          $subject    = env('APP_NAME').' - Reset Password';
              $emails     = [
                  array(
                      'email' => $check->email,
                      'name'  => $check->name,
                      'type'  => 'to'
                  ),
              ];
              
              $link       =  env('APP_FRONTEND_URL')."reset-password/{$check->email}/{$check->remember_token}";
  
              Mail::to($emails)->send(new ForgotPasswordNotification($subject,$check,$link));
  
              $message = trans("translate.successfullySend");
              return response()
              ->json(['status'=>200 ,'datas' =>["messages" => $message], 'errors' => null])
              ->withHeaders([
                  'Content-Type'          => 'application/json',
              ])
              ->setStatusCode(200);
  
        }else{
  
          $errors = [
              "email"   => ["Email / Company Code not match"]
          ];
          if (app()->getLocale() == "id") {
  
            $errors = [
                "email"   => ["Alamat Surel / Kode Perusahaan tidak cocok"]
            ];
  
          }
        
          return response()
          ->json(['status'=>422 ,'datas' => $company_code, 'errors' => $errors])
          ->withHeaders([
              'Content-Type'          => 'application/json',
          ])
          ->setStatusCode(422);
        }
  
    }

    
    public function authenticate(Request $request) {

        $validator = Validator::make($request->all(), [
           'email' 		    => 'required|email',
           'company_code' 	=> 'required|max:255',
           'password' 		=> 'required'
       ]);
       if ($validator->fails()) {
           return response()
           ->json(['status'=>422 ,'datas' => null, 'errors' => $validator->errors()])
           ->withHeaders([
             'Content-Type'          => 'application/json',
             ])
               ->setStatusCode(422);  
       }
       

       $email 		= $request->input('email');
       $password 	= $request->input('password');
       $company_code = $request->input('company_code');
       
       
       $selectedUser = User::where([['email', '=', $email],["password", "=" , sha1($request->password)],['status', '=', "ACTIVATE"]])->with(['company_detail','role'])->whereHas('company_detail',function($q) use ($company_code){
           return $q->where([["code",$company_code],["status","ACTIVATE"]]);
         })->first();
       
       if ($selectedUser) {
         
           $token = $this->jwt($selectedUser);
           
           $data = ['access_token' => $token, 'refresh_token' => $selectedUser->remember_token, 'type' => 'bearer','exp' => time() + 1440*60];
           return response()
               ->json(['status'=>200 ,'datas' => $data, 'errors' => null])
               ->withHeaders([
                 'Content-Type'          => 'application/json',
                 ])
                   ->setStatusCode(200);

       } else {
           $message = trans("translate.userLoginFail");

           return response()
           ->json(['status'=>422 ,'datas' => null, 'errors' => ['message' => [$message]]])
           ->withHeaders([
             'Content-Type'          => 'application/json',
             ])
               ->setStatusCode(422);

       }

    }

    private function jwt(User $user) {
        
        $payload = [
            'iss' => "bearer",
            'sub' => $user,
            'iat' => time(),
            'exp' => time() + 1440*60 // token kadaluwarsa setelah 3600 detik
        ];
        
        return JWT::encode($payload, env('APP_KEY'), 'HS256');
    
    }


}