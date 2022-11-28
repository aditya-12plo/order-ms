<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;
use Log;
use PDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use AESGCM\AESGCM;
use Illuminate\Support\Facades\Queue;
use App\Jobs\ExampleJob;

use App\Models\PasswordReset;
use DB,Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\AdditionalController as AdditionalFs;


class IndexController extends Controller
{
   	
    /* 
    error status code
        200	success
        404	Not Found (page or other resource doesn’t exist)
        401	Not authorized (not logged in)
        403	Logged in but access to requested area is forbidden
        400	Bad request (something wrong with URL or parameters)
        422	Unprocessable Entity (validation failed)
        500	General server error
    */

    public function index()
    {
        $message = trans('translate.welcome')." ".env('APP_ENV')." ".env('APP_NAME')." By Aplikasi Pemuda Indonesia";
        return response()
        ->json(['status'=>200 ,'datas' => ['message' => $message], 'errors' => null])
        ->withHeaders([
            'Content-Type'          => 'application/json',
            ])
        ->setStatusCode(200);
    }

}