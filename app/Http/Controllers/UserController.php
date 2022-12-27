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

    public function index(Request $request){

		$auth					= $request->auth;
        $permision_roles     	= $request->permision_role;
		$credentials			= $request->credentials;
		$company_group			= $request->company_group;
		$company_detail			= $auth->company_detail;
 
		
 		$checkPermission    = $this->additional->checkPermission($permision_roles,'UserController','method_read');
		
		if($checkPermission){			

			$perPage        		= $request->per_page;
			$sort_field     		= $request->sort_field;
			$sort_type      		= $request->sort_type;
			
			$company_code     		= $request->company_code;
			$role_code     			= $request->role_code;
			$name	                = $request->name;
			$email	                = $request->email;
			$status                 = $request->status;
			$startDate    			= $request->startDate;
			$endDate    			= $request->endDate;
			$download    			= $request->download;
			
			if(!$sort_field){
				$sort_field = "id_user";
				$sort_type = "DESC";
			}
	
			if(!$perPage){
				$perPage 	= 10;
			} 

			if($company_detail->code == "OMS"){

				$query = User::with(["company_detail","role"])->whereNotIn('id_user', [$auth->id_user])->orderBy($sort_field,$sort_type);

			}else{
				$id_companys	= [$auth->id_company];

				if(count($company_group) > 0){
					$id_companys	= $this->additional->groupCompany($auth->id_company,$company_group);
				}

				$query = User::with(["company_detail","role"])->whereIn("id_company",$id_companys)->whereNotIn('id_user', [$auth->id_user])->orderBy($sort_field,$sort_type);


			}
					
			if ($company_code) {
				$like = "%{$company_code}%";
				$query->whereHas('company_detail',function($q) use ($like){
                    return $q->where('code','LIKE',$like);
                });
			}

			if ($role_code) {
				$like = "%{$role_code}%";
				$query->whereHas('role',function($q) use ($like){
                    return $q->where('code','LIKE',$like);
                });
			}

			if ($startDate && $endDate) {
				$query = $query->whereBetween('created_at', [urldecode($startDate), urldecode($endDate)]);
				// $query = $query->whereDate('created_at','>=',urldecode($startDate))->whereDate('created_at', '<=', urldecode($endDate));
			}
	
						
			if ($name) {
				$like = "%{$name}%";
				$query = $query->where('name', 'LIKE', $like);
			}
						
			if ($email) {
				$like = "%{$email}%";
				$query = $query->where('email', 'LIKE', $like);
			}
									
			if ($status) {
				$query = $query->where('status', $status);
			}
	
			if($download){
				$response    = $query->get();
				if($download == "download"){
					return $this->downloadData($response);
				}else{
				
					return response()
								->json(['status'=>200 ,'datas' => $response, 'credentials' => $credentials, 'errors' => null])
								->withHeaders([
								  'Content-Type'          => 'application/json',
								  ])
								->setStatusCode(200);
				}

			}else{

				if($perPage < 0){
                    $perPage =  $query->count();
                }

				$response   =  $query->paginate($perPage);
			
				return response()
							->json(['status'=>200 ,'datas' => $response, 'credentials' => $credentials, 'errors' => null])
							->withHeaders([
							  'Content-Type'          => 'application/json',
							  ])
							->setStatusCode(200);
			}
	

		}else{
			$message = trans("translate.unauthorizedAccess");
            return response()
            ->json(['status'=>401 ,'datas' => null, 'credentials' => $credentials, 'errors' => ["messages" => [$message]]])
            ->withHeaders([
                'Content-Type'          => 'application/json',
            ])
            ->setStatusCode(401);
		}

    }

}