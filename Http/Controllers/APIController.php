<?php

namespace App\Http\Controllers\Auth;
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;
use App\models\User;  
use App\models\RoleUser; 

use App\models\Doctor; 
use App\models\Patient;  
 
use App\models\LoginHistory;
use App\models\Dose;

use App\models\Question;
use App\models\SubQuestion;
use App\models\DoseLogs;

use App\models\DosetakenLogs;

use App\models\ClosenotificationLogs;

use App\services\EmailService;
use App\services\APIService;


use App\models\Devicelist;
use Mail;
use DB;
use stdClass;

use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
 

use App\Http\Controllers\BaseController;

class APIController extends BaseController
{
	 
    public function __construct() {
         
    } 
	 
	protected function createNewToken($token){ 
		return response()->json([
			'access_token' 	=> $token,
			'expires_in' 	=> auth('api')->factory()->getTTL(), 
			'token_type' 	=> 'Bearer'			
		]);	 
    } 
	
	public function login(){ 
		$credentials = ['email' => request('email'), 'password' => request('password'), 'status' =>'A'];       
		if (!$token = auth('api')->attempt($credentials)) {
			return response()->json([
										'success'	=> false, 
										'message'	=> 'Invalid credentials or Account suspended. Contact to administrator !',
										'data'		=> []
									], 401); 
		}else{
			$user = auth('api')->user();			 
			if($user->roles[0]->name == 'PATIENT'){			
				
				$loginHistoryObj = new LoginHistory();
				$loginHistoryObj->userid 		= $user->id;
				$loginHistoryObj->loginstatus 	= 1;
				$loginHistoryObj->token			= $token;				
				$loginHistoryObj->save();
				
				
				$deviceObj = Devicelist::where('user_id','=',$user->id)->get();
				if(count((array)$deviceObj)>0){
					foreach($deviceObj as $key=>$value){					
						//$value->notification1=0;
						//$value->notification2=0;
						//$value->notification3=0;
						//$value->save();	
						
						DB::table('devicelist')
						->where('user_id','=',$user->id)						 
						->update(['notification1' => 0,	'notification2' => 0, 'notification3' => 0]); 
																
					}
				}					
				
				
				
				$patientObj =  Patient::with('dose')
										->where('userid','=',$user->id)
									    ->where('status','=','A')
									    ->first(); 
					
					$doseencounterid='';
					if(count((array)$patientObj->dose)>0){						
						$doseencounterid  	= $patientObj->dose->doseencounterid;
					}

					if($doseencounterid!=''){ 					
				
						return response()->json([
										'success' => true, 
										'message' => 'Successfully logged in !',
										'data'=> [
													[
													  "userId" 					=> $user->id,
													  "name" 					=> $user->name,
													  "email" 					=> $user->email,
													  "access_token"			=> $token,
													  "expires_in" 				=> auth('api')->factory()->getTTL(),
													  'token_type'   			=> 'Bearer',
													  'doseencounterid'			=> $doseencounterid,
													  'cycle_complete'			=> false
													]
												]
										], 200); 
					}else{

						$doseObjChk = Dose::where('completestatus','=',1)
										  ->where('userid','=',$user->id)
										  ->take(1)
										  ->get();	
				
						if(count($doseObjChk)>0){
							return response()->json([
												'success'	=> false, 
												'message' 	=> 'Your encounter has already been closed. Please reach out to your gastroenterologist or physician for further care !', 
												'data'		=>[[
													  'doseencounterid'			=> null,
													  'cycle_complete'			=> true
												]]
											], 200); 
							
						}else{
							return response()->json([
												'success'	=> false, 
												'message' 	=> 'Your dose is not yet prescribed. Please contact admin !', 
												'data'		=>[[
													'doseencounterid'			=> null,
													'cycle_complete'			=> false
												
												]]
											], 200); 	
						}	
					}		
			}else{ 
				return response()->json([
											'success'	=> false, 
											'message' 	=> 'Account suspended. Contact to administrator !', 
											'data'		=>[]
										], 401); 
			}
        } 
    } 
	
	public function logout() {
		$token = auth('api')->getToken();
		if (!$token) {
			 return response()->json([
									'success'	=> false, 
									'message'	=> 'Token not provided !',
									'data'		=> [] 
								],401);
		 }else{			 
			$user = auth('api')->user();
			$device_token 	= request('device_token');
			$userid 		= $user->id;	 
			
			if($user){
				try {
					auth('api')->logout(); 
					
					$deviceObj = Devicelist::where('device_token','=',$device_token)
									->where('user_id','=',$userid)
									->where('logoutstatus','=',0)
									->take(1)
									->get();
									
						 	
					if(count($deviceObj)>0){						
						$deviceObj[0]->logoutstatus = 1;
						$deviceObj[0]->save(); 
					}
					
					$loginHistoryObj = new LoginHistory();
					$loginHistoryObj->userid 		= $userid;
					$loginHistoryObj->logoutstatus 	= 1;
					$loginHistoryObj->token			= $token;				
					$loginHistoryObj->save();
					  
					return response()->json([
										'success'	=> true, 
										"token" 	=> $token,
										'message'	=> 'Successfully logged out !',
										'data'		=> [] 
									],200);
				} catch (JWTException $exception) {
					return response()->json([
						'success' 	=> false,
						'message' 	=> 'Sorry, the user cannot be logged out',
						'data'		=> [] 
					], 500);
				}
		
			}else{
				return response()->json([
										'success'=> false, 
										'message'=>'Invalid access token or token expired !',
										'data'=>[] 
									],401);
			}
		 }
    }
	 
    public function refresh() {
		 $token = auth('api')->getToken();
		 if (!$token) {
			 return response()->json([
									'success'	=> false, 
									'message'	=> 'Access Token not provided !',
									'data'		=> [] 
								],401);
		 }else{		
			return $this->createNewToken(auth('api')->refresh()); 
		 }
    } 
   	
	public function profile() {
		$token = auth('api')->getToken();	
	 
		if (!$token) {
			return response()->json([
									'success'	=> false, 
									'message'	=> 'Access Token not provided !',
									'data'		=> [] 
								],401);
		}else{
				 
			$user = auth('api')->user();		 
			if($user){
				if($user->status=='A'){ 
					$patientObj =  Patient::with('dose')
										->where('userid','=',$user->id)
									    ->where('status','=','A')
									    ->first(); 
					
					$doseencounterid='';
					if(count((array)$patientObj->dose)>0){						
						$doseencounterid  	= $patientObj->dose->doseencounterid;
					}					
					if($doseencounterid!=''){ 		
						return response()->json([
									'success'=> true, 											
									'message'=>'Profile get !',
									'data' => [
												[
												  "patient"	=> [
													  "id" 			=> $user->id,
													  "name" 		=> $patientObj->firstName,
													  "email" 		=> $patientObj->email,
													  "code"		=> $patientObj->patient_code,
													  "sex" 		=> $patientObj->sex,
													  "age" 		=> $patientObj->age, 
													  "mobileno" 	=> $patientObj->mobileNo,
													  "diagnosis"   => $patientObj->diagnosis
												  ], 
												  "doseencounterid"		=> $doseencounterid ,
												  'cycle_complete'		=> false
												]
											  ]
										],200); 
					}else{
						
						
						if($patientObj->diagnosis=='U'){
							$text = "ulcerative colitis";
						}else{
							$text = "crohn's disease";
						}
						
						$doseObjChk = Dose::where('completestatus','=',1)
										  ->where('userid','=',$user->id)
										  ->take(1)
										  ->get();	
				
						if(count($doseObjChk)>0){
							 
							if($doseObjChk[0]->healthyclose==0){
								$msg = "Congratulations! You have successfully completed your prednisone taper. We will follow up with you in 7 days and then again in 30 days to check on your health from ".$text." perspective.";
							}else{
								$msg = "Your ".$text." flare has failed to respond to prednisone, so please reach out to your gastroenterologist or physician for further care and management.";
							}
							 
							return response()->json([
									'success'=> true, 											
									'message'=> $msg,
									'data' => [
												[
												  "patient"	=> [
													  "id" 			=> $user->id,
													  "name" 		=> $patientObj->firstName,
													  "email" 		=> $patientObj->email,
													  "code"		=> $patientObj->patient_code,
													  "sex" 		=> $patientObj->sex,
													  "age" 		=> $patientObj->age, 
													  "mobileno" 	=> $patientObj->mobileNo,
													  "diagnosis"   => $patientObj->diagnosis
												  ], 
												  "doseencounterid"		=> '',
												  'cycle_complete'		=> true
												  
												]
											  ]
										],200); 
							 
							
						}else{
							
							return response()->json([
									'success'=> true, 											
									'message'=> 'Does is not yet prescribed. Please contact to admin !',
									'data' => [
												[
												  "patient"	=> [
													  "id" 			=> $user->id,
													  "name" 		=> $patientObj->firstName,
													  "email" 		=> $patientObj->email,
													  "code"		=> $patientObj->patient_code,
													  "sex" 		=> $patientObj->sex,
													  "age" 		=> $patientObj->age, 
													  "mobileno" 	=> $patientObj->mobileNo,
													  "diagnosis"   => $patientObj->diagnosis
												  ], 
												  "doseencounterid"		=> '',
												  'cycle_complete'		=> true
												  
												]
											  ]
										],200); 
							
							
							 	
						}	
					}
				}else{
					return response()->json([
											'success'	=> false, 
											'message'	=> 'User is inactive. Logout the app !',
											'data' 		=> []
										],401); 
				}
			}else{
				return response()->json([
										'success'	=> false, 
										'message'	=> 'Invalid access token or token expired !',
										'data'		=> [] 
									],401);
			}				 
		}			
	}
	 	
	 
	public function history() {
		$token = auth('api')->getToken();	 
		 
		if (!$token) {
			return response()->json([
									'success'	=> false, 
									'message'	=> 'Access Token not provided !',
									'data'		=> [] 
								],401);
		}else{
				 
			$user = auth('api')->user();		 
			if($user){
				
				if($user->status=='A'){ 
					
					 
					$encountertotaldose = DosetakenLogs::where('userid','=',$user->id) 
														->where('status','=','A')
														->orderBy('created_at','desc')
														->Paginate(5);
					 
					$pagination = [];  
				 
					$pagination["total_record_available"]	= $encountertotaldose->total();
					$pagination["per_page_record"]			= $encountertotaldose->perPage();
					$pagination["current_page_number"]		= $encountertotaldose->currentPage();
					$pagination["last_page_number"]			= $encountertotaldose->lastPage();
					$pagination["next_page_url"]			= $encountertotaldose->nextPageUrl();
					$pagination["prev_page_url"]			= $encountertotaldose->previousPageUrl();
					$pagination["record_from"]				= $encountertotaldose->firstItem();
					$pagination["record_to"]				= $encountertotaldose->lastItem();
						 
					
					$historydata=[]; 
					$s=0; 
					foreach($encountertotaldose as $key=>$value){
						
						$historydata[$s]['dosedate']		= $value->dosetakendate; 
						$historydata[$s]['morningdose']		= $value->morningdose."";
						$historydata[$s]['eveningdose']		= $value->eveningdose."";
						
						 
						
						$s++;
						 
					}
					
					return response()->json([
											 'success'		=> true, 
											 'message'		=> 'History get !',
											 'pagination' 	=> $pagination, 
											 'log' 			=> $historydata
										],200); 
					
				
				}else{
					return response()->json([
											'success'	=> false, 
											'message'	=> 'User is inactive. Logout the app !',
											'data' 		=> []
										],401); 
				}
				
			}else{
				return response()->json([
										'success'	=> false, 
										'message'	=> 'Invalid access token or token expired !',
										'data'		=> [] 
									],401);
			}	
		}
	}
	
	public function changepassword() {
		$token = auth('api')->getToken();		 
		 
		if (!$token) {
			return response()->json([
									'success'	=> false, 
									'message'	=> 'Access Token not provided !',
									'data'		=> [] 
								],401);
		}else{
				 
			$user = auth('api')->user();		 
			if($user){
				
				if($user->status=='A'){

					$oldpassword = request('oldpassword');
					$newpassword = request('newpassword');
					
					if($oldpassword!='' && $newpassword!=''){					
							$credentials = ['email' => $user->email, 'password' => $oldpassword];       
							if(auth('api')->attempt($credentials)){
								
								User::where('email','=',$user->email)->update( ['password'=>bcrypt($newpassword)]);
								
								return response()->json([
													 'success'		=> true, 
													 'message'		=> 'Password updated successfully !'  
												],200); 
												
							}else{
									return response()->json([
													'success'	=> false, 
													'message'	=> 'Old password does not match !',
													'data' 		=> []
												],200); 
							}				
					}else{
						return response()->json([
													'success'	=> false, 
													'message'	=> 'Please enter old and new password !',
													'data' 		=> []
												],200); 
					}
				
				}else{
					return response()->json([
											'success'	=> false, 
											'message'	=> 'User is inactive. Logout the app !',
											'data' 		=> []
										],401); 
				}
				
			}else{
				return response()->json([
										'success'	=> false, 
										'message'	=> 'Invalid access token or token expired !',
										'data'		=> [] 
									],401);
			}	
		}
	}
	
	public function deviceregister() {
		$token = auth('api')->getToken();		 
		 
		if (!$token) {
			return response()->json([
									'success'	=> false, 
									'message'	=> 'Access Token not provided !',
									'data'		=> [] 
								],401);
		}else{
				 
			$user = auth('api')->user();		 
			if($user){
				
				if($user->status=='A'){

					$device_token 	= request('device_token');
					$os_version 	= request('os_version');
					$model 			= request('model');
					$user_id 		= $user->id;
					 
					if($device_token!='' && $user_id!=''){	

					
							$deviceobj = Devicelist::where('device_token','=',$device_token)
											  ->where('user_id','=',$user->id)											  
											  ->take(1)
											  ->get(); 
					
							if(count($deviceobj)>0){								 
								$deviceobj[0]->logoutstatus	=  0;
								$deviceobj[0]->save(); 
								$id = $deviceobj[0]->id;
							}else{
								$deviceobj = new Devicelist();
								$deviceobj->device_token 	=  $device_token;
								$deviceobj->os_version 		=  $os_version;
								$deviceobj->model 			=  $model;
								$deviceobj->user_id 		=  $user_id;
								$deviceobj->logoutstatus	=  0;
								$deviceobj->save();	
								$id = $deviceobj->id;
							} 
							
							if($id>0){
								return response()->json([
													 'success'		=> true, 
													 'message'		=> 'Device info added successfully !',
													 'data'			=> []	
												],200); 
							}else{
							
								return response()->json([
													'success'	=> false, 
													'message'	=> 'Failed to add device info !',
													'data' 		=> []
												],200); 	
							
							}							
							 			
					}else{
						return response()->json([
													'success'	=> false, 
													'message'	=> 'Device token not available !',
													'data' 		=> []
												],200); 
					}
				
				}else{
					return response()->json([
											'success'	=> false, 
											'message'	=> 'User is inactive. Logout the app !',
											'data' 		=> []
										],401); 
				}
				
			}else{
				return response()->json([
										'success'	=> false, 
										'message'	=> 'Invalid access token or token expired !',
										'data'		=> [] 
									],401);
			}	
		}
	}
	
	public function checkemail(){			
		 $email = request('email'); 
		 $flag = false;
		 if($email!=''){ 
			$userObj = User::where('email','=',$email)
						->where('status','=','A')
						->first();  
						
			 if(count((array)$userObj)>0 && $userObj->id>0){
				
				$newpass = mt_rand(100000, 999999); 
				$userObj->pass_reset_token = $newpass;
				$userObj->save();
				if($userObj->pass_reset_token!=''){					 
					$flag = true;						
				}
				$subject = "Password Recovery Mail" ; 
				$sentby  = getenv('ADMIN_EMAIL');
				$toemail = $userObj->email;
				$toname  = $userObj->name; 
				$linkhash= $newpass; 
				$data = array(    
								'sentby'                => $sentby,
								'toemail'               => $toemail,
								'toname'                => $toname,
								'subject'               => $subject,
								'adminemail'            => getenv('ADMIN_EMAIL'),
								'adminname'             => getenv('ADMIN_NAME'), 
								'userObj'               => $userObj,
								'linkhash'              => $linkhash
							); 
				 
					Mail::send( 'emails.password_reset_mail', $data, function( $message ) use ($data,$userObj,$linkhash)
					{   
						 
						$message->to( $userObj->email , $userObj->name ); 
									
						$message->from( $data['adminemail'], $data['adminname'])
								->subject($data['subject']); 
						
					}); 
					
					return response()->json([
										'success'	=> true, 
										'message'	=> 'An email has been sent to your email address. The email has a verification code, please paste the verification code in the field below to prove that you are the owner of this account !',
										'data'		=> ["email"=>$email]
									], 200);	
					 
				 
			 }else{
				 return response()->json([
										'success'	=> false, 
										'message'	=> 'Entered email does not exist or inactive !',
										'data'		=> []
									], 200);				  
			 }
				
		 }else{
			return response()->json([
										'success'	=> false, 
										'message'	=> 'Please enter a valid email id !',
										'data'		=> []
									], 401); 
			 
		 }			 
	
	}
	
	public function codeverification(){
		 
		 $email = request('email'); 
		 $otp 	= request('otp'); 
		 
		 if($email!='' && $otp!=''){ 
			 $userObj = User::where('email','=',$email)
							->where('pass_reset_token','=',$otp)
							->where('status','=','A')
							->first();  
			 
			   if(count((array)$userObj)>0 && $userObj->id>0){ 
					return response()->json([
										'success'	=> true, 
										'message'	=> 'Valid varification code !',
										'data'		=> ["email"=>$email,"otp"=>$otp]
									], 200);	
				  
			   }else{ 				  
				   return response()->json([
										'success'	=> false, 
										'message'	=> 'Invalid varification code !',
										'data'		=> []
									], 200);									
				  
			   } 
			   
		 }else{			 
			 return response()->json([
										'success'	=> false, 
										'message'	=> 'Please enter a valid varification code !',
										'data'		=> []
									], 401); 
		 }		
		 
	}
	
	public function updatepassword(){
		$msg			= "";
		$email 			= request('email'); 
		$otp 			= request('otp'); 
		$newpassword 	= request('newpassword'); 
		$confpassword 	= request('confpassword');	
		
		if($email!='' && $otp!='' && $newpassword!='' && $confpassword!=''){       
		
			if($newpassword==''){           
				 $msg     = "Please enter new password !"; 
			}else if(strlen($newpassword)<6){             
				 $msg     = "New password should be more then five characters long !"; 
			}else if($confpassword==''){             
				 $msg     = "Please enter confirm password !"; 
			}else if($newpassword!=$confpassword){             
				 $msg     = "New password and confirm password are not same !"; 
			}
			
			if($msg!=''){
				return response()->json([
										'success'	=> false, 
										'message'	=> $msg,
										'data'		=> []
									], 200);	
			}else{
				
				$userObj = User::where('email','=',$email)
							->where('pass_reset_token','=',$otp)
							->where('status','=','A')
							->first();  
			 
				if(count((array)$userObj)>0 && $userObj->id>0){				
					$userObj->pass_reset_token = '';
					$userObj->password = bcrypt($newpassword); 
					$userObj->save();
					
					 return response()->json([
										'success'	=> true, 
										'message'	=> 'Password updated successfully !',
										'data'		=> []
									], 200);
					 
				}else{
					 return response()->json([
										'success'	=> false, 
										'message'	=> 'Invalid varification code !',
										'data'		=> []
									], 200);
				}
				
			}			
			
        
		}else{			 
			return response()->json([
										'success'	=> false, 
										'message'	=> 'Please fill all the required field !',
										'data'		=> []
									], 401);	
				
		} 
		
	}
	
	public function updatenotificationresponse(){		
		
		$token = auth('api')->getToken();	 
		 
		if (!$token) {
			return response()->json([
									'success'	=> false, 
									'message'	=> 'Access Token not provided !',
									'data'		=> [] 
								],401);
		}else{
				 
			$user = auth('api')->user();		 
			if($user){
				
				$patientObj =  Patient::where('userid','=',$user->id)
								->where('status','=','A')
								->first(); 
				
				if($patientObj->diagnosis=='U'){
					$text = "ulcerative colitis";
				}else{
					$text = "crohn's disease";
				}

				
				if($user->status=='A'){ 			 
					
					$closenotificationObj = ClosenotificationLogs::where('userid','=',$user->id)->first();				
					
					$name = $user->name;
					$days 	= request('days');
					$option = request('option');   // 'Y' or 'N'
					$msg	= "";
					if($days=='7'){
						if($option=='Y'){					
							$msg = "Thank you for using our prednisone taper application! For any questions or further management of your ".$text." please reach out to your gastroenterologist.";
						}
						
						$closenotificationObj->seventhstatus = $option;
						$closenotificationObj->save();
					}
					
					if($days=='30'){
						if($option=='Y'){					
							$msg = "Thank you for using our prednisone taper application! For any questions or further management of your ".$text." please reach out to your gastroenterologist.";
						}
						
						$closenotificationObj->thirtiethstatus = $option;
						$closenotificationObj->save();
					}
					  	
							
					
					$msginemail  =  "Your patient, ".$name.", took prednisone again for ".$text." flare within ".$days." days of completing the taper.";
					
					
					//////////////////////////////////////////////////////////////////
					
					$subject = "Admin Notification :: Patient ".$name ; 
					$sentby  = getenv('ADMIN_EMAIL');
					 
					 
					$data = array(    
									'sentby'                => $sentby,
									'toemail'               => getenv('ADMIN_EMAIL'),
									'toname'                => getenv('ADMIN_NAME'),
									'subject'               => $subject,
									'adminemail'            => getenv('ADMIN_EMAIL'),
									'adminname'             => getenv('ADMIN_NAME'), 
									'user'               	=> $user,
									'msginemail'            => $msginemail
							); 
							
					//if($option=='Y'){				
				 
						Mail::send( 'emails.admin_notification_mail', $data, function( $message ) use ($data,$msginemail)
						{   
							 
							$message->to( getenv('ADMIN_EMAIL') , getenv('ADMIN_NAME') ); 
										
							$message->from( $data['adminemail'], $data['adminname'])
									->subject($data['subject']); 
							
						}); 
					
					//}
					  
					/////////////////////////////////////////////////////////////////
					 
					return response()->json([
											 'success'		=> true, 
											 'message'		=> $msg,
											 'data' 		=> []											  
										],200);
					
				
				}else{
					return response()->json([
											'success'	=> false, 
											'message'	=> 'User is inactive. Logout the app !',
											'data' 		=> []
										],401); 
				}
				
			}else{
				return response()->json([
										'success'	=> false, 
										'message'	=> 'Invalid access token or token expired !',
										'data'		=> [] 
									],401);
			}	
		} 	
		
	}
	
}