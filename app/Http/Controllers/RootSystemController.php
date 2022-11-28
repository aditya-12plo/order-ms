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
use Laravel\Lumen\Http\ResponseFactory;
use App\Http\Controllers\AdditionalController as AdditionalFs;


class RootSystemController extends Controller
{

    public function generatePdf()
    {
       /**
       * for save pdf file to server
       */
      $html = "<h1>Test</h1>";
      return PDF::loadHTML($html)->setPaper('a4', 'landscape')->setWarnings(false)->save('storage/pdf/coba.pdf');
      

      /**
       * for download pdf file
       */
    //   $data = ["satu" => 1];
    //   return PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('pdf.coba', $data)->download('invoice.pdf');


      /**
       * for view pdf file
       */
      // $data = ["satu" => 1];
      // return PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('pdf.coba', $data)->stream();
        
    }

    
    public function pdfencode()
    {
      $name         = "coba.pdf";
      $avatar_path  = storage_path('pdf') . '/' . $name;
      $file         = file_get_contents($avatar_path);
      $extension    = pathinfo($avatar_path, PATHINFO_EXTENSION);
      $mimetype     = $this->getFileMimeType($extension);
      $pdf_b64      = chunk_split(base64_encode($file));
      $datas        = [
        'document'      => $pdf_b64,
        "mime_type"     => $mimetype,
        "document_type" => "shippingLabel"
      ];
      
      return response()
      ->json(['status'=>200 ,'datas' => $datas, 'errors' => null])
      ->withHeaders([
          'Content-Type'          => 'application/json',
          ])
      ->setStatusCode(200);
    

    }

    private function getFileMimeType($extension) {
        $mimet = array( 
            'csv' => 'text/csv',
            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',
    
            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',
    
            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',
    
            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',
    
            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',
    
            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',
            'docx' => 'application/msword',
            'xlsx' => 'application/vnd.ms-excel',
            'pptx' => 'application/vnd.ms-powerpoint',
    
    
            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );
    
        if (isset( $mimet[$extension] )) {
         return $mimet[$extension];
        } else {
         return 'application/octet-stream';
        }
     }
  
}