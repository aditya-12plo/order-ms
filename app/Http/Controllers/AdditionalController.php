<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use DB,Mail;
use Firebase\JWT\JWT;
use App\Mail\EmailNotification;
 
use App\Models\Bot\Telegram;


class AdditionalController extends Controller {
    

    public function __construct()
    {

    }

    public static function sendEmail($emails=[],$subject,$content,$attachment=array()){
        try {
            Mail::to($emails)->send(new EmailNotification($subject,$content ,$attachment));
            if (Mail::failures()) {
                return new Error(Mail::failures()); 
            }else{
                return response()->json("Email Sent!");
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage());                
        }
    }

    public static function sendBotTelegram($subject,$content,$chatId = ""){

        if(empty($chatId)){
            $chatId = env('TELE_CHAT_ID');
        }

        $apiTelegram    = new Telegram($chatId);
        $subjectTitle   =  env('APP_ENV').' '. env('APP_NAME').' '.$subject;
        $contentData    = "\n\n<b>Message :</b>\n\n";
        $contentData    .= $content;

        $response       = $apiTelegram->sendMessage($subjectTitle,$contentData);
        return response()->json($response);
        
    }
    
    public static function checkPermission($request, $controllerName , $rolePermissionMethod){
        $permision_roles     = $request->auth->permision_role;

        if(count($permision_roles) > 0){

            foreach($permision_roles as $key => $permision){
                
                if($permision->permission->controller == $controllerName){
                    
                    return $permision->$rolePermissionMethod;

                }

            }

        }
        
        return false;

    }



    public static function encryptString($input){

        
        $output  = Crypt::encryptString($input);
        // $output  = base64_encode(gzcompress($input,9));
    
        return $output;
    }



    public static function decompress($input){
        try {
            $output                = @Crypt::decryptString($input);
            return $output;
        }catch (DecryptException $e) {
            return false;
        }

        // $output  = base64_decode(gzuncompress($input));
        // return $output;
    }

}