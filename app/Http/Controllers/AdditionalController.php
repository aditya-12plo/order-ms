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
use App\Models\Log;
use App\Models\Company;
use App\Models\Backup;


class AdditionalController extends Controller {
    

    public function __construct()
    {

    }

    public static function insertLog($instance,$channel,$level=200 , $url="api" , $message="api" , $context="api" , $extra="api"){
        $insert = [
            'instance'    => $instance,
            'channel'     => $channel,
            'message'     => $message,
            'level'       => $level,
            'context'     => $context,
            'url'         => $url,
            'extra'       => $extra,
            'ip'          => app('request')->server('REMOTE_ADDR'),
            'user_agent'  => app('request')->server('HTTP_USER_AGENT')
        ];
        Log::create($insert);
        
    }


    public static function groupCompanyDetail($id_company,$datas){
        
        foreach($datas as $data){
           if($data->id_company == $id_company){
                return $data->company_detail;
           }
        }

        return false;
    }


    public static function groupCompany($id_company,$datas){
        $arrays = [$id_company];
        foreach($datas as $data){
            array_push($arrays,$data->id_company);
        }
        return $arrays;
    }

    public static function getAllCompanyIds(){
        $arrays = [];
        $datas  = Company::get();
        foreach($datas as $data){
            array_push($arrays,$data->id_company);
        }
        return $arrays;
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
    
    public static function checkPermission($permision_roles, $controllerName , $rolePermissionMethod){
        
        if($permision_roles){

            if(count($permision_roles) > 0){

                foreach($permision_roles as $permision){
                    
                    if($permision->code == $controllerName){
                        
                        return $permision->$rolePermissionMethod;
    
                    }
    
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


    public static function cleanString($string) {
        return strtoupper(preg_replace('/[^A-Za-z0-9\-]/', '', $string)); // Removes special chars.
    }

    public static function cleanStringWithoutSpace($string) {
        $string = str_replace(' ', '', $string);
        return strtoupper(preg_replace('/[^A-Za-z0-9\-]/', '', $string)); // Removes special chars.
    }

}