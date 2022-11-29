<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use View,Input,Session,File,DB,Mail;
use Illuminate\Support\Facades\Crypt;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use Log;
use PDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;

use App\Http\Controllers\AdditionalController as Additional;

use App\Models\User;
use App\Models\Company;


class UserController extends Controller
{
    protected $additional;

	public function __construct(){
		$this->middleware('jwt.auth');
        $this->additional           = new Additional;
    }
	
    public function detailUserAccess(Request $request){
		$auth					= $request->auth;
        // $permision_roles     	= $request->permision_role;
		$credentials			= $request->credentials;
		// $company_group			= $request->company_group;

		return response()
				->json(['status'=>200 ,'datas' => $auth, 'credentials' => $credentials, 'errors' => null])
				->withHeaders([
				  'Content-Type'          => 'application/json',
				])
				->setStatusCode(200);

	}

    public function roleUser(Request $request){
		// $auth					= $request->auth;
        $permision_roles     	= $request->permision_role;
		$credentials			= $request->credentials;
		// $company_group			= $request->company_group;

		return response()
				->json(['status'=>200 ,'datas' => $permision_roles, 'credentials' => $credentials, 'errors' => null])
				->withHeaders([
				  'Content-Type'          => 'application/json',
				])
				->setStatusCode(200);

	}

}