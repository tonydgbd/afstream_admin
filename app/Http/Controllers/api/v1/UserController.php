<?php

namespace App\Http\Controllers\api\v1;

use Modules\Setting\Entities\Settings;
use Modules\Setting\Entities\Currency;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Modules\Artist\Entities\Artist;
use Illuminate\Support\Facades\URL;
use Modules\Plan\Entities\Plan;
use Illuminate\Http\Request;
use Modules\Language\Entities\Language;
use App\AppVersion;
use App\Favourite;
use App\AdminAudioPayment;
use App\UserPurchasedPlan;
use DB;
use Illuminate\Support\Carbon;
use App\User; 
use stdClass;

class UserController extends Controller
{

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
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateProfileDetail(Request $request) 
    {
        if($request->isMethod('post')){
            $user = Auth::user();
            $postData = $request->all();
            $data = [];

            if(!empty($user)){

                
                if($image = $request->file('userImage')){
                    $name = 'user'.$user['id'].'-'.time().'.webp';
                    $data['image'] = $name;
                    upload_image($image, public_path().'/images/user/', $name, '512x512');
                    if(!empty($user) && $user->image != '') {
                        delete_file_if_exist(public_path().'/images/user/'.$user->image);
                    }
                }
                
                
                if(isset($postData['name']) && !empty($postData['name'])){
                    $data['name'] = $postData['name']; 
                }
                if(isset($postData['mobile']) && !empty($postData['mobile'])){
                    $data['mobile'] = $postData['mobile'];
                }
                if(isset($postData['dob']) && !empty($postData['dob'])){
                    $data['dob'] = $postData['dob'];
                }
                if(isset($postData['gender']))
                {
                    $data['gender'] = $postData['gender'];
                }
                if(isset($postData['password']) && !empty($postData['password'])){
                    $data['password'] = Hash::make($postData['password']);
                }    
                
                $updateUsersDetail = $user->update($data);
                
                $appVersion = AppVersion::find('1');   
                $setLang = $this->getUserLang();
                $userDetail = $this->setUserDetails();
                    
                $response['status'] = $this->successStatus;
                $response['msg'] = "Profile successfully updated."; 
                $response['data'] = $userDetail;
                if($setLang == 'null' || $setLang == ''){
                    $response['selectedLanguage'] = [];
                }else{
                    $response['selectedLanguage'] = $setLang;
                }
                $response['login_token'] = '';
                $response['appVersion'] = $appVersion['latest_version'];
                $response['adminBaseUrl'] = url('/');

            }else{
                $response['status'] = $this->errorStatus;
                $response['msg'] = 'Unauthenticated.';
                $response['data'] =  new stdClass();
            }
                
                return response()->json($response);
            
        }
    }


    public function userDetails(Request $request){        

        $user = Auth::user();
        if(!empty($user)){
            $paymentGateways = [];

            $userDetail = $this->setUserDetails();                

            $userDetail['plan_expiry_date'] = '';
            $userDetail['in_app_purchase'] = 0;
            $userDetail['plan_detail'] = new stdClass();
            $userDetail['download'] = 0;
            $userDetail['ads'] = 1;

            $settings = Settings::pluck('value','name');            
            $getPlan = Plan::find(Auth()->user()->plan_id);            
            $currencyId = Settings::where('name', 'default_currency_id')->first();
            $set_tax = Settings::where('name', 'set_tax')->first();
            $tax = Settings::where('name', 'tax')->first(); 
            if(!empty($currencyId['value'])){
                $currency = Currency::select('code','symbol')->where('id',$currencyId['value'])->first();
                if(!empty($currency)){
                    $userDetail['currencyCode'] = $currency->code;
                    $userDetail['currencySymbol'] = $currency->symbol; 
                }
            }
            if(!empty($set_tax['value'] && $set_tax['value'] != 0)){                    
                $userDetail['tax'] = strval($tax['value']);                   
            }else{
                $userDetail['tax'] = '0';
            }
            
            $userPlanDetail = UserPurchasedPlan::where(['user_id' => Auth::user()->id, ['expiry_date', '>=', date('Y-m-d')] ])->orderBy('id', 'desc')->limit(1)->get();
            if(!$userPlanDetail->isEmpty()){
                $planDetail = json_decode($userPlanDetail[0]->plan_data);
                $userDetail['download'] = $planDetail->is_download; 
                $userDetail['ads'] = $planDetail->show_advertisement;
                $userDetail['plan_expiry_date'] = date('d-m-Y', strtotime($userPlanDetail[0]->expiry_date));
                $userDetail['plan_detail'] = $planDetail;
            }
            
            if(!empty(env('RAZORPAY_KEY'))){
                $userDetail['admin_rzp_key'] = env('RAZORPAY_KEY');
            }else{
                $userDetail['admin_rzp_key'] = '';
            }

            if(!empty(env('RAZORPAY_SECRET'))){
                $userDetail['admin_rzp_secret'] = env('RAZORPAY_SECRET');                 
            }else{
                $userDetail['admin_rzp_secret'] = '';                
            }
            $userDetail['payment_type'] = 'razorpay';

            if(!empty(env('YOUTUBE_API_KEY'))){
                $userDetail['google_api_key'] = env('YOUTUBE_API_KEY');
            }else{
                $userDetail['google_api_key'] = '';
            }

            if(!empty(env('YOUTUBE_CHANNEL_KEY'))){
                $userDetail['yt_channel_key'] = env('YOUTUBE_CHANNEL_KEY');
            }else{
                $userDetail['yt_channel_key'] = '';
            }            

            if(!empty(env('YT_COUNTRY_CODE'))){
                $userDetail['yt_country_code'] = env('YT_COUNTRY_CODE');
            }else{
                $userDetail['yt_country_code'] = '';
            }

            $is_youtube = Settings::where('name', 'is_youtube')->first();      

            if(!empty($is_youtube) && $is_youtube['value'] == 1) {  
                $userDetail['is_youtube'] = 1;
            }else{
                $userDetail['is_youtube'] = 0;
            }            
            

            if(isset($settings['is_razorpay']) && !empty($settings['is_razorpay']) && $settings['is_razorpay'] == 1){
                $payment_gateways['razorpay']['razorpay_key'] = env('RAZORPAY_KEY');
                $payment_gateways['razorpay']['razorpay_secret'] = env('RAZORPAY_SECRET');                
            }else{
                $payment_gateways['razorpay'] = new stdClass();
            }            
            if(isset($settings['is_paypal']) && !empty($settings['is_paypal']) && $settings['is_paypal'] == 1){
                $payment_gateways['paypal']['paypal_client_id'] = env('PAYPAL_CLIENT_ID');
                $payment_gateways['paypal']['paypal_secret'] = env('PAYPAL_SECRET');
                $payment_gateways['paypal']['paypal_mode'] = env('PAYPAL_MODE');                
            }else{
                $payment_gateways['paypal'] = new stdClass();
            }
            if(isset($settings['is_stripe']) && !empty($settings['is_stripe']) && $settings['is_stripe'] == 1){
                $payment_gateways['stripe']['stripe_client_id'] = env('STRIPE_CLIENT_ID');
                $payment_gateways['stripe']['stripe_secret'] = env('STRIPE_SECRET');
                $payment_gateways['stripe']['stripe_merchant_display_name'] = env('STRIPE_MERCHANT_DISPLAY_NAME');
                $payment_gateways['stripe']['stripe_merchant_country_code'] = env('STRIPE_MERCHANT_COUNTRY_CODE');
                $payment_gateways['stripe']['stripe_merchant_country_identifier'] = env('STRIPE_MERCHANT_IDENTIFIER');
            }else{
                $payment_gateways['stripe'] = new stdClass();
            }
            if(isset($settings['is_paystack']) && !empty($settings['is_paystack']) && $settings['is_paystack'] == 1){
                $payment_gateways['paystack']['paystack_public_key'] = env('PAYSTACK_PUBLIC_KEY');
                $payment_gateways['paystack']['paystack_secret_key'] = env('PAYSTACK_SECRET_KEY');
                $payment_gateways['paystack']['paystack_payment_key'] = env('PAYSTACK_PAYMENT_URL');
            }else{
                $payment_gateways['paystack'] = new stdClass();
            }

            $setDefaultLan = Language::select('language_code')->where('is_default', 1)->first();
            if(!empty($setDefaultLan)){     
                $userDetail['app_language'] = $setDefaultLan->language_code;
            }
            
            $in_app_purchase =  Plan::where('in_app_purchase','1')->first();
            if(!empty($in_app_purchase)){
                $userDetail['in_app_purchase'] = 1;
            }else{
                $userDetail['in_app_purchase'] = 0;
            }

            $response['status'] = $this->successStatus;
            $response['msg'] = 'User details successfully found';            
            $response['data'] = $userDetail;        
            $response['payment_gateways'] = $payment_gateways;        
        }else{
            $response['data'] =  new stdClass();
            $response['status'] = $this->errorStatus;
            $response['msg'] = 'Unauthenticated.';
        }
        return response()->json($response);    
    
    }
    
    public function deleteAccountPermanent(Request $request){
        
        $resp = [];
        if(isset($request->user_id) && $request->user_id == Auth::user()->id){           
            $users = DB::table('users')->where('id', Auth::user()->id)->delete();
            $userHistory = DB::table('user_history')->where('user_id', Auth::user()->id)->delete();
            $playlist = DB::table('playlists')->where('user_id', Auth::user()->id)->delete(); 
            $favourite = DB::table('favourites')->where('user_id', Auth::user()->id)->delete(); 
            $couponManagement = DB::table('coupon_management')->where('user_id', Auth::user()->id)->delete(); 
            $comments = DB::table('comments')->where('user_id', Auth::user()->id)->delete(); 
            $users = DB::table('reply')->where('user_id', Auth::user()->id)->delete(); 
            $resp['status'] = $this->successStatus;
            $resp['msg'] = __('frontWords.delete_account_msg');
        }else{
            $resp['status'] = $this->errorStatus;
            $resp['msg'] = __('frontWords.something_wrong');
        }
        return response()->json($resp);
    }
    
    
    
    /**
     * Get User Purchase Details. 
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserPurchaseHistory(Request $request) 
    {
        if($request->isMethod('get')){

            if(!empty(Auth::user())){
                
                $data['audioPurchaseHistory'] = AdminAudioPayment::where(['user_id' => Auth::user()->id, 'status' => '1'])->orderBy('id','desc')->get()->toArray();
                $data['planPurchaseHistory'] = UserPurchasedPlan::where('user_id' , Auth::user()->id)->where('order_id','!=','')->orderBy('id','desc')->get()->toArray();
                
                if(!empty($data['audioPurchaseHistory']) || !empty($data['planPurchaseHistory'])){
                    $response['status'] = $this->successStatus;
                    $response['msg'] = "User purchase history successfully found.";                    
                    $response['data'] =  $data;
                    
                }else{
                    $response['status'] = $this->errorStatus;
                    $response['msg'] = 'User purchase history does not found.';
                    $response['data'] =  [];
                }
            }else{
                $response['status'] = $this->errorStatus;
                $response['msg'] = 'Unauthenticated.';
                $response['data'] =  [];
            }
            return response()->json($response);
        }
    }
    
    
    function setUserDetails($userDetail = null){

            $userDetail = User::userDetail(Auth::id());            

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

            if(isset($userDetail['audio_download_list']) && !$userDetail['audio_download_list'] == null){
                $userDetail['audio_download_list'] = $userDetail['audio_download_list'];
            }else{
                $userDetail['audio_download_list'] = '';
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
                $userDetail['purchased_plan_date'] = date('d-m-Y', strtotime($userDetail['purchased_plan_date']));
            }else{
                $userDetail['purchased_plan_date'] = '';
            } 
            return $userDetail;          
    }

}

