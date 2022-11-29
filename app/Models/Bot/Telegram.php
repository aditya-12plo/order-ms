<?php

namespace App\Models\Bot;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Client as Client;
use Psr\Http\Message\RequestInterface;
use GuzzleHttp\Exception\ClientErrorResponseException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use DateTime;
use App\Http\Controllers\AdditionalController as Additional;

 
class Telegram
{

    protected $additional;
    private static $timezone = 'Asia/Jakarta';

    public function __construct($chat_id)
    {
        $this->additional       = new Additional;
        $this->client           = new Client();
        $this->app_env          = env('APP_ENV');
        $this->token            = env('TELEGRAM_BOT_TOKEN');
        $this->endpoint 		= 'https://api.telegram.org';
        $this->chat_id          = $chat_id;
    }

    public function getUpdate()
    {
        $url = $this->endpoint."/bot{$this->token}/getUpdates";
      
        $body   = [];

 
        try{
             
            $response = $this->client->request('GET',$url, [
                'connect_timeout'   => 10,
                'read_timeout'      => 30,
                'query'             => $body
            ]);

            $res    = json_decode($response->getBody(),TRUE);
            return ["status" => 200, "datas" => $res, "errors" => null];
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $exception      = (string) $e->getResponse()->getBody();
                $exception      = json_decode($exception);
                $array          = new JsonResponse($exception, $e->getCode());
                $subject        = $this->app_env." getUpdate Model Bot/Telegram Issue";
                $content        = json_encode($array);
                $attachment     = []; 
                $group_module   = "api";
                $this->additional->sendEmail($subject,$content,$attachment,$group_module);
                return ["status" => 422, "datas" => null, "errors" => $array->original];

              } else {
                $array          = new JsonResponse($e->getMessage(), 503);
                $subject        = $this->app_env." getUpdate Model Bot/Telegram Issue";
                $content        = json_encode($array);
                $attachment     = []; 
                $group_module   = "api";
                $this->additional->sendEmail($subject,$content,$attachment,$group_module);
                return ["status" => 422, "datas" => null, "errors" => $array];
              }
        }catch (BadResponseException $e) {
            $exception      = $e->getResponse()->getBody()->getContents();
            $array          = new JsonResponse($exception, 503);
            $subject        = $this->app_env." getUpdate Model Bot/Telegram Issue";
            $content        = $exception;
            $attachment     = []; 
            $group_module   = "api";
            $this->additional->sendEmail($subject,$content,$attachment,$group_module);
            return ["status" => 422, "datas" => null, "errors" => $array];
        } 

    }



    public function getMe()
    {
        $url = $this->endpoint."/bot{$this->token}/getMe";
      
        $body   = [];

 
        try{
             
            $response = $this->client->request('GET',$url, [
                'connect_timeout'   => 10,
                'read_timeout'      => 30,
                'query'             => $body
            ]);

            $res    = json_decode($response->getBody(),TRUE);
            return ["status" => 200, "datas" => $res, "errors" => null];
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $exception      = (string) $e->getResponse()->getBody();
                $exception      = json_decode($exception);
                $array          = new JsonResponse($exception, $e->getCode());
                $subject        = $this->app_env." getMe Model Bot/Telegram Issue";
                $content        = json_encode($array);
                $attachment     = []; 
                $group_module   = "api";
                $this->additional->sendEmail($subject,$content,$attachment,$group_module);
                return ["status" => 422, "datas" => null, "errors" => $array->original];

              } else {
                $array          = new JsonResponse($e->getMessage(), 503);
                $subject        = $this->app_env." getMe Model Bot/Telegram Issue";
                $content        = json_encode($array);
                $attachment     = []; 
                $group_module   = "api";
                $this->additional->sendEmail($subject,$content,$attachment,$group_module);
                return ["status" => 422, "datas" => null, "errors" => $array];
              }
        }catch (BadResponseException $e) {
            $exception      = $e->getResponse()->getBody()->getContents();
            $array          = new JsonResponse($exception, 503);
            $subject        = $this->app_env." getMe Model Bot/Telegram Issue";
            $content        = $exception;
            $attachment     = []; 
            $group_module   = "api";
            $this->additional->sendEmail($subject,$content,$attachment,$group_module);
            return ["status" => 422, "datas" => null, "errors" => $array];
        } 

    }


    public function sendMessage($subject, $content)
    {
        $datas = $subject;
        $datas .= "\n\n".$content;
        $url = $this->endpoint."/bot{$this->token}/sendMessage";
      
        $body   = [
            "chat_id"           => $this->chat_id,
            "text"              => $datas,
            "parse_mode"        => "html"
        ];

 
        try{
             
            $response = $this->client->request('POST',$url, [
                'connect_timeout'   => 10,
                'read_timeout'      => 30,
                'query'             => $body
            ]);

            $res    = json_decode($response->getBody(),TRUE);
            return ["status" => 200, "datas" => $res, "errors" => null];
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $exception      = (string) $e->getResponse()->getBody();
                $exception      = json_decode($exception);
                $array          = new JsonResponse($exception, $e->getCode());
                $subject        = $this->app_env." sendMessage Model Bot/Telegram Issue";
                $content        = json_encode($array);
                $attachment     = []; 
                $group_module   = "api";
                $this->additional->sendEmail($subject,$content,$attachment,$group_module);
                return ["status" => 422, "datas" => null, "errors" => $array->original];

              } else {
                $array          = new JsonResponse($e->getMessage(), 503);
                $subject        = $this->app_env." sendMessage Model Bot/Telegram Issue";
                $content        = json_encode($array);
                $attachment     = []; 
                $group_module   = "api";
                $this->additional->sendEmail($subject,$content,$attachment,$group_module);
                return ["status" => 422, "datas" => null, "errors" => $array];
              }
        }catch (BadResponseException $e) {
            $exception      = $e->getResponse()->getBody()->getContents();
            $array          = new JsonResponse($exception, 503);
            $subject        = $this->app_env." sendMessage Model Bot/Telegram Issue";
            $content        = $exception;
            $attachment     = []; 
            $group_module   = "api";
            $this->additional->sendEmail($subject,$content,$attachment,$group_module);
            return ["status" => 422, "datas" => null, "errors" => $array];
        } 

    }
}