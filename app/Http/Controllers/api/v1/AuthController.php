<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Http\Controllers\Controller;
use Modules\Setting\Entities\Settings;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash;
use Modules\Plan\Entities\Plan;
use App\UserPurchasedPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use App\Mail\SendEmailOtp;
use App\Mail\WelcomeMail;
use Seshac\Otp\Otp;
use App\AppVersion;
use App\Favourite;
use Validator;
use App\User; 
use Password;
use stdClass;
use DB;

class AuthController extends Controller
{

    use SendsPasswordResetEmails;

    public $successStatus = true;
    public $errorStatus = false;
    public $errorMsg = 'Something went wrong.';
    
    public function getUserLang(){
        if(isset(Auth::user()->id)){
            $language = Favourite::where('user_id', Auth::user()->id)->get();
            $setLanguage = [];
            if(sizeof($language) > 0){
                $setLanguage = json_decode($language[0]->user_language);
            }
        }
        return $setLanguage;
    }
    
    
    // NEW USER REGISTER
    public  function register(Request $request) {

        if($request->isMethod('post')){
            
            $response = [];
            $postData = $request->all();        
            $validator = Validator::make($postData, [
                'name' => 'required', 
                'email' => 'required|email|unique:users,email',
                //'mobile' => 'required',
                'password' => 'required|min:6',
                'password_confirmation' => 'required|min:6|same:password',
                    
            ]);

            if ($validator->fails()) {
                $response['status'] =  $this->errorStatus;
                $response['msg'] = $validator->errors()->first();
                $response['data'] =  new stdClass(); 
                return response()->json($response); die;
            }

                $dataArr = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'mobile' => $request->mobile
                ];
                
                if(isset($request->accept_term_and_policy) && !empty($request->accept_term_and_policy) && $request->accept_term_and_policy == '1'){
                    $dataArr['accept_term_and_policy'] = '1';  // Check term and privacy policy accepetance      
                }
            
                $createUser = User::create($dataArr);

                $checkPlan = Plan::where(['plan_amount'=>0,'status'=>'1'])->orderBy('id','desc')->get();
                if(sizeof($checkPlan) > 0){
                    $planValid = $checkPlan[0]->validity;
                    $expiry_date = date("Y-m-d", strtotime("+".$planValid.' day', strtotime(date('Y-m-d'))));
                    $addPlan = UserPurchasedPlan::create([
                        'user_id' => $createUser->id,
                        'plan_id' => $checkPlan[0]->id,
                        'plan_data' => json_encode($checkPlan[0]),
                        'payment_data' => json_encode([]),
                        'currency' => '$',
                        'expiry_date' => $expiry_date
                    ]); 
                    if($addPlan){
                        $artistCountUpdate = User::where('id', $createUser->id)->update(['plan_id' => $checkPlan[0]->id,'purchased_plan_date'=>date("Y-m-d", strtotime(date('Y-m-d')))]);
                    }
                }

                if($createUser){

                    Auth::attempt(['email' => $request->email,'password' => $request->password]);
                    $user = Auth::user();
                    if($user['status'] == '0'){
                        $response['status'] =  $this->errorStatus;
                        $response['msg'] = 'Your account status is not active';
                        $response['data'] =  new stdClass(); 
                        return response()->json($response);
                    }
                    $accessToken = $user->createToken('accessToken')->accessToken;                                     
                    $userDetail = User::userDetail($createUser->id);    
                    $appVersion = AppVersion::find('1');
                    $setLang = $this->getUserLang();
                    
                    if(!empty($userDetail) && !empty($accessToken)){

                        $userDetail = $this->setUserDetails($userDetail);                        
                        $response['status'] = $this->successStatus;
                        
                        $checkSetting = Settings::where('name', 'LIKE', '%wel_mail%')->first();
                        $checkSmtpSetting = Settings::where('name', 'is_smtp')->first();
                        if(!empty($checkSetting) && $checkSetting->value == 1 && !empty($checkSmtpSetting) && $checkSmtpSetting->value == 1){
                            $pass = $request->password;
                            $dataArr['url'] = url('/home');
                            $dataArr['password'] = $pass;
                            if(!empty(env('MAIL_PASSWORD'))){
                                try {
                                    Mail::to($request->email)->send(new WelcomeMail($dataArr));
                                }catch (\Exception $e) {
                                }
                            }
                        }
                
                        $response['msg'] = __('frontWords.register_success');
                        $response['login_token'] = $accessToken;
                        $response['data'] = $userDetail;
                        $response['appVersion'] = $appVersion['latest_version'];
                        if($setLang == 'null' || $setLang == ''){
                            $response['selectedLanguage'] = [];
                        }else{
                            $response['selectedLanguage'] = $setLang;
                        }
                        $response['adminBaseUrl'] = url('/');
                    }
                    return response()->json($response);

                } else{
                    $response['status'] =  $this->errorStatus;
                    $response['msg'] = $this->errorMsg;
                    $response['data'] =  new stdClass(); 
                    return response()->json($response);
                }
        }        
    }

    // LOGIN USER
    public function login(Request $request) {
       
        if($request->isMethod('post')){

            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|min:6'
            ]);   

            if ($validator->fails()) {
                $response['status'] =  $this->errorStatus;
                $response['msg'] = $validator->errors()->first();
                $response['data'] =  new stdClass(); 
                return response()->json($response); die;
            }

            if (Auth::attempt(['email' => $request->email,'password' => $request->password])) {     

                $user = Auth::user();
                if($user['status'] == '0'){
                    $response['status'] =  $this->errorStatus;
                    $response['msg'] = 'Your account status is not active';
                    $response['data'] =  new stdClass(); 
                    return response()->json($response);
                }

                $token = $user->createToken('accessToken')->accessToken;                      
                $userDetail = User::userDetail(Auth::id());  
                $appVersion = AppVersion::find('1');
                $setLang = $this->getUserLang();
                
                if(!empty($userDetail) && !empty($token)){

                    $userDetail = $this->setUserDetails();                      
                    $response['status'] = $this->successStatus;
                    $response['msg'] = "User login successfully.";
                    $response['login_token'] = $token;                          
                    $response['data'] = $userDetail;
                    if($setLang == 'null' || $setLang == ''){
                        $response['selectedLanguage'] = [];
                    }else{
                        $response['selectedLanguage'] = $setLang;
                    }
                    $response['appVersion'] = $appVersion['latest_version'];
                    $response['adminBaseUrl'] = url('/'); //public_path();
                }
                return response()->json($response);      
            
            } else { 
                $response['status'] = $this->errorStatus;     
                $response['msg'] = "Credential does not match";
                $response['data'] = new stdClass(); 
                
                return response()->json($response);    
            }       
       }
    }

 

    public function forgotPassword(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
        ]);   

        if ($validator->fails()) {
            $response['status'] =  $this->errorStatus;
            $response['msg'] = $validator->errors()->first();
            $response['data'] =  new stdClass(); 
            return response()->json($response); die;
        }

        $userDetail = User::where('email',$request->email)->first();
        if(!empty($userDetail)){

            $otp =  Otp::generate($request->email);
            
            if($otp->status == '1'){
                $userDetail['otp'] = $otp->token;

                $checkSmtpSetting = Settings::where('name', 'is_smtp')->first();
                if(!empty($checkSmtpSetting) && $checkSmtpSetting->value == 1){
                    if(!empty(env('MAIL_PASSWORD'))){
                        try {
                            Mail::to($request->email)->send(new SendEmailOtp($userDetail));
                        }catch (\Exception $e) {
                            $response['smtp_error'] = __('adminWords.smtp_setting_error');
                        }
                        
                        $response['status'] =  $this->successStatus;
                        $response['msg'] = 'OTP has been successfully sent to email.';
                    }else{
                        $response['msg'] = 'SMTP configuration error found.';
                    }
                }else{
                    $response['msg'] = 'SMTP configuration error found.';
                }
                $response['status'] =  $this->successStatus;               
                $response['data'] =  new stdClass(); 
                return response()->json($response);
            }else{
                $response['status'] =  $this->errorStatus;
                $response['msg'] = 'Reached the maximum 10 times to generate OTP';
                $response['data'] =  new stdClass(); 
                return response()->json($response);
            }

        }else{
            $response['status'] =  $this->errorStatus;
            $response['msg'] = 'Email does not exist.';
            $response['data'] =  new stdClass(); 
            return response()->json($response);
        }

    }


    public function resetPassword(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|min:6',
            'confirm_password' => 'required|min:6|same:password', 
            'otp' => 'required|numeric|digits:6',
        ], [
            'otp.required'  => 'Please enter OTP.',
            'otp.digits'  => 'Please enter valid OTP..',
        ]);   

        if ($validator->fails()) {
            $response['status'] =  $this->errorStatus;
            $response['msg'] = $validator->errors()->first();
            $response['data'] =  new stdClass(); 
            return response()->json($response); die;
        }

        $userDetail = User::where('email',$request->email)->first();
        if(!empty($userDetail)){
            $verify = Otp::validate($request->email, $request->otp);
            
            if($verify->status == '1'){ 
                $data = [];
                $user = User::where('email',$request->email)->first(); 
    
                $updatePassword = $user->update(array('password' => Hash::make($request->password)));
                    if($updatePassword){ 
                        DB::table('otps')->where(['identifier' => $request->email])->update(['expired' => '1']);
                        $response['status'] =  $this->successStatus;
                        $response['msg'] = 'Password successfully updated.';
                        $response['data'] =  new stdClass(); 
                        return response()->json($response);
            
                    }else{
                        $response['status'] =  $this->errorStatus;
                        $response['msg'] = $this->errorMsg;
                        $response['data'] =  new stdClass(); 
                        return response()->json($response);
                    }           
            }else{
                $response['status'] =  $this->errorStatus;
                $response['msg'] = $verify->message;
                $response['data'] =  new stdClass(); 
                return response()->json($response);
            }

        }else{
            $response['status'] =  $this->errorStatus;
            $response['msg'] = 'Email does not exist.';
            $response['data'] =  new stdClass(); 
            return response()->json($response);
        }


    }
   

    Public function logout(Request $request){
        
        $token = $request->user()->token();
        if(!empty($token)){
            $token->delete();
            $response['status'] = $this->successStatus;
            $response['msg'] = 'User logout Successfully.';
        }else{
            $response['status'] = $this->errorStatus;
            $response['msg'] = 'Unauthenticated.';
        }
        return response()->json($response); 
        
    }

    function setUserDetails($userDetail = null){
            
            if(empty($userDetail)){
                $userDetail = User::userDetail(Auth::id());            
            }

            if(isset($userDetail['email_verified_at']) && !$userDetail['email_verified_at'] == null){ 
                $userDetail['email_verified_at'] = $userDetail['email_verified_at'];
            }else{
                $userDetail['email_verified_at'] = '';
            }

            if(isset($userDetail['dob']) && !$userDetail['dob'] == null){
                $userDetail['dob'] = $userDetail['dob'];
            }else{
                $userDetail['dob'] = '';
            }

            if(isset($userDetail['image']) && !$userDetail['image'] == null){
                $userDetail['image'] = 'images/user/'.$userDetail['image'];
            }else{
                $userDetail['image'] = '';
            }

            if(isset($userDetail['address']) && !$userDetail['address'] == null){
                $userDetail['address'] = $userDetail['address'];
            }else{
                $userDetail['address'] = '';
            }

            if(isset($userDetail['billing_detail']) && !$userDetail['billing_detail'] == null){
                $userDetail['billing_detail'] = $userDetail['billing_detail'];
            }else{
                $userDetail['billing_detail'] = '';
            }

            if(isset($userDetail['country_id']) && !$userDetail['country_id'] == null){
                $userDetail['country_id'] = $userDetail['country_id'];
            }else{
                $userDetail['country_id'] = '';
            }

            if(isset($userDetail['state_id']) && !$userDetail['state_id'] == null){
                $userDetail['state_id'] = $userDetail['state_id'];
            }else{
                $userDetail['state_id'] = '';
            }

            if(isset($userDetail['city_id']) && !$userDetail['city_id'] == null){
                $userDetail['city_id'] = $userDetail['city_id'];
            }else{
                $userDetail['city_id'] = '';
            }

            if(isset($userDetail['braintree_id']) && !$userDetail['braintree_id'] == null){
                $userDetail['braintree_id'] = $userDetail['braintree_id'];
            }else{
                $userDetail['braintree_id'] = '';
            }

            if(isset($userDetail['pincode']) && !$userDetail['pincode'] == null){
                $userDetail['pincode'] = $userDetail['pincode'];
            }else{
                $userDetail['pincode'] = '';
            }       
            if(isset($userDetail['purchased_plan_date']) && !$userDetail['purchased_plan_date'] == null){
                $userDetail['purchased_plan_date'] = $userDetail['purchased_plan_date'];
            }else{
                $userDetail['purchased_plan_date'] = '';
            }    
            return $userDetail;          
    }
}
