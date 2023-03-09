<?php
namespace Modules\Setting\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Setting\Entities\Settings;
use Modules\Location\Entities\AllCountry;
use Modules\Setting\Entities\PaymentMethod;

class ConfigController extends Controller
{
    public function mail(){
        return view('setting::mail');
    }

    public function mail_update(Request $request){
        
        if(isset($request->is_smtp)){
            $updateSetting = Settings::updateOrCreate(['name'=>'is_smtp'],['value'=>$request->is_smtp]);
        }else{
            $updateSetting = Settings::updateOrCreate(['name'=>'is_smtp'],['value'=>0]);
        }
        
        if(isset($request->wel_mail) && !empty($request->wel_mail)){
            $updateSetting = Settings::updateOrCreate(['name'=>'wel_mail'],['value'=>$request->wel_mail]);
        }else{
            $updateSetting = Settings::updateOrCreate(['name'=>'wel_mail'],['value'=>0]);
        }

        $env_update = $this->changeEnv([
            'MAIL_FROM_NAME' => $request->MAIL_FROM_NAME,
            'MAIL_DRIVER' => $request->MAIL_DRIVER,
            'MAIL_HOST' => $request->MAIL_HOST,
            'MAIL_PORT' => $request->MAIL_PORT,
            'MAIL_USERNAME' => $request->MAIL_USERNAME,
            'MAIL_FROM_ADDRESS' => $string = preg_replace('/\s+/', '', $request->MAIL_FROM_ADDRESS),
            'MAIL_PASSWORD' => $request->MAIL_PASSWORD,
            'MAIL_ENCRYPTION' => $request->MAIL_ENCRYPTION
        ]);

        if($env_update){
            $resp = array('status'=>1, 'msg'=>__('adminWords.mail').' '.__('adminWords.settings').' '.__('adminWords.updated_msg'));
        }else{
            $resp = array('status'=>0, 'msg'=>__('adminWords.error_msg'));
        }
        echo json_encode($resp);
    }

    public function api(){
        return view('setting::api');
    }

    public function api_update(Request $request, $type){
        $input = $request->except('_token');
        $isChecked = 0;
        $appendArr = [];
        $paymentArr = [];

        if($type == 'razor' && isset($input['is_razorpay'])){
            $rules = [ 'RAZORPAY_KEY' => 'required', 'RAZORPAY_SECRET' => 'required' ];
            $checkValidate = validation($input, $rules);
            if($checkValidate['status'] == 0){
                echo json_encode($checkValidate); exit;
            }
            $appendArr = [ 'RAZORPAY_KEY' => $input['RAZORPAY_KEY'], 'RAZORPAY_SECRET' => $input['RAZORPAY_SECRET'], 'is_razorpay'=>1 ];
            $paymentArr = [ 'gateway_name' => 'razorpay'];
        }
        if($type == 'paypal' && isset($input['is_paypal'])){
            $rules = [ 'PAYPAL_MODE' => 'required', 'PAYPAL_CLIENT_ID' => 'required', 'PAYPAL_SECRET' => 'required' ];
            $checkValidate = validation($input, $rules);
            if($checkValidate['status'] == 0){
                echo json_encode($checkValidate); exit;
            }
            $appendArr = [ 'PAYPAL_CLIENT_ID' => $input['PAYPAL_CLIENT_ID'],'PAYPAL_SECRET' => $input['PAYPAL_SECRET'],'PAYPAL_MODE' => $input['PAYPAL_MODE'], 'is_paypal'=>1 ];
            $paymentArr = [ 'gateway_name' => 'paypal'];
        }
        if($type == 'payu' && isset($input['is_payu'])){
            $rules = [ 'PAYU_METHOD' => 'required', 'PAYU_MERCHANT_KEY' => 'required', 'PAYU_MERCHANT_SALT' => 'required', 'PAYU_AUTH_HEADER' => 'required' ];
            $checkValidate = validation($input, $rules);
            if($checkValidate['status'] == 0){
                echo json_encode($checkValidate); exit;
            }
            $appendArr = [ 'PAYU_METHOD' => $input['PAYU_METHOD'], 'PAYU_DEFAULT' => $input['PAYU_DEFAULT'], 'PAYU_MERCHANT_KEY' => $input['PAYU_MERCHANT_KEY'], 'PAYU_MERCHANT_SALT' => $input['PAYU_MERCHANT_SALT'], 'PAYU_AUTH_HEADER'=>$input['PAYU_AUTH_HEADER'], 'PAY_U_MONEY_ACC' => isset($input['PAY_U_MONEY_ACC']) ? 'true' : 'false', 'is_payu'=>1 ];
            $paymentArr = [ 'gateway_name' => 'payu'];
        }
        if($type == 'paytm' && isset($input['is_paytm'])){
            $rules = [ 'PAYTM_ENVIRONMENT' => 'required', 'PAYTM_MERCHANT_ID' => 'required', 'PAYTM_MERCHANT_KEY' => 'required', 'PAYTM_MERCHANT_WEBSITE' => 'required']; 
            $checkValidate = validation($input, $rules);
            if($checkValidate['status'] == 0){
                echo json_encode($checkValidate); exit;
            }
            $appendArr = [ 'PAYTM_ENVIRONMENT' =>  $input['PAYTM_ENVIRONMENT'], 'PAYTM_MERCHANT_ID' => $input['PAYTM_MERCHANT_ID'],'PAYTM_MERCHANT_KEY' => $input['PAYTM_MERCHANT_KEY'], 'PAYTM_MERCHANT_WEBSITE' => $input['PAYTM_MERCHANT_WEBSITE'], 'is_paytm'=>1 ];
            $paymentArr = [ 'gateway_name' => 'paytm'];
        }

        if($type == 'stripe' && isset($input['is_stripe'])){
            $rules = [ 'STRIPE_CLIENT_ID' => 'required', 'STRIPE_SECRET' => 'required'];
            $checkValidate = validation($input, $rules);
            if($checkValidate['status'] == 0){
                echo json_encode($checkValidate); exit;
            }
            $appendArr = [ 'STRIPE_CLIENT_ID' =>  $input['STRIPE_CLIENT_ID'], 'STRIPE_SECRET' => $input['STRIPE_SECRET'],'STRIPE_MERCHANT_DISPLAY_NAME' => $input['STRIPE_MERCHANT_DISPLAY_NAME'],'STRIPE_MERCHANT_COUNTRY_CODE' => $input['STRIPE_MERCHANT_COUNTRY_CODE'],'STRIPE_MERCHANT_IDENTIFIER' => $input['STRIPE_MERCHANT_IDENTIFIER'], 'is_stripe'=>1 ];
            $paymentArr = [ 'gateway_name' => 'stripe'];
        }

        if($type == 'instamojo' && isset($input['is_instamojo'])){
            $rules = [ 'IM_API_KEY' => 'required', 'IM_AUTH_TOKEN' => 'required', 'IM_URL' => 'required'];
            $checkValidate = validation($input, $rules);
            if($checkValidate['status'] == 0){
                echo json_encode($checkValidate); exit;
            }
            $appendArr = [ 'IM_API_KEY' =>  $input['IM_API_KEY'], 'IM_AUTH_TOKEN' => $input['IM_AUTH_TOKEN'], 'IM_URL' => $input['IM_URL'], 'is_instamojo'=>1 ];
            $paymentArr = [ 'gateway_name' => 'instamojo'];
        }
        if($type == 'paystack' && isset($input['is_paystack'])){
            $rules = [ 'PAYSTACK_PUBLIC_KEY' => 'required', 'PAYSTACK_SECRET_KEY' => 'required', 'PAYSTACK_PAYMENT_URL' => 'required', 'MERCHANT_EMAIL' => 'required' ];
            $checkValidate = validation($input, $rules);
            if($checkValidate['status'] == 0){
                echo json_encode($checkValidate); exit;
            }
            $appendArr = [ 'PAYSTACK_PUBLIC_KEY' =>  $input['PAYSTACK_PUBLIC_KEY'], 'PAYSTACK_SECRET_KEY' => $input['PAYSTACK_SECRET_KEY'], 'PAYSTACK_PAYMENT_URL' => $input['PAYSTACK_PAYMENT_URL'], 'MERCHANT_EMAIL' => $input['MERCHANT_EMAIL'], 'is_paystack'=>1 ];
            $paymentArr = [ 'gateway_name' => 'paystack'];
        }
        if($type == 'braintree' && isset($input['is_braintree'])){
            $rules = [ 'BRAINTREE_ENV' => 'required', 'BRAINTREE_MERCHANT_ID' => 'required', 'BRAINTREE_PUBLIC_KEY' => 'required', 'BRAINTREE_PRIVATE_KEY' => 'required' ];
            $checkValidate = validation($input, $rules);
            if($checkValidate['status'] == 0){
                echo json_encode($checkValidate); exit;
            }
            $appendArr = [ 'BRAINTREE_ENV' =>  $input['BRAINTREE_ENV'], 'BRAINTREE_MERCHANT_ID' => $input['BRAINTREE_MERCHANT_ID'], 'BRAINTREE_PUBLIC_KEY' => $input['BRAINTREE_PUBLIC_KEY'], 'BRAINTREE_PRIVATE_KEY' => $input['BRAINTREE_PRIVATE_KEY'], 'is_braintree'=>1 ];
            $paymentArr = [ 'gateway_name' => 'braintree'];
    }
        if($type == 'manual_pay' && isset($input['is_manual_pay']) && isset($input['is_manual_pay'])){
            $rules = [ 'BANK_NAME' => 'required', 'BRANCH_NAME' => 'required', 'IFSC_CODE' => 'required', 'SWIFT_CODE' => 'required', 'ACCOUNT_NUMBER' => 'required', 'ACCOUNT_NAME' => 'required' ];
            $checkValidate = validation($input, $rules);
            if($checkValidate['status'] == 0){
                echo json_encode($checkValidate); exit;
            }
            $appendArr = [ 'BANK_NAME' =>  $input['BANK_NAME'], 'BRANCH_NAME' => $input['BRANCH_NAME'], 'IFSC_CODE' => $input['IFSC_CODE'], 'SWIFT_CODE' => $input['SWIFT_CODE'], 'ACCOUNT_NUMBER' => $input['ACCOUNT_NUMBER'], 'ACCOUNT_NAME' => $input['ACCOUNT_NAME'], 'is_manual_pay'=>1 ];
            $paymentArr = [ 'gateway_name' => 'manual_pay'];
        }

        $success = 0;
        $env_update = $this->changeEnv($appendArr);
        foreach($appendArr as $key=>$val){
            $insert = Settings::updateOrCreate(['name'=>$key],['value'=>$val]);
            $success = 1;
        }
        $paymentGateway = PaymentMethod::updateOrCreate($paymentArr, ['status' => 1]);
        if($env_update && $success) {
           $resp = array('status'=>1, 'msg'=>__('adminWords.settings').' '.__('adminWords.success_msg'));
        }else {
            $resp = array('status'=>0, 'msg'=>__('adminWords.settings').' '.__('adminWords.could_not_err'));
        }
        echo json_encode($resp);
    }

    public function updateStatus(Request $request){  
        $checkValidate = validation($request->except('_token'),['status'=>'required', 'type'=>'required']);
        if($checkValidate['status'] == 1){
            $update = Settings::updateOrCreate(['name'=>$request->type],['value'=>$request->status]);
            if(isset($request->gateway_name)){
                $paymentGateway = PaymentMethod::updateOrCreate(['gateway_name' => $request->gateway_name], ['status' => 0]);
            }
            $resp = ['status'=>1,'msg'=>__('adminWords.data').' '.__('adminWords.success_msg')];
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }


    public function social_login(){
        return view('setting::socialLogin');
    }

    public function saveSocialLoginData(Request $request, $type){
        $input = $request->except('_token');
        $isChecked = 0;
        if($type == 'google' && isset($input['is_google'])){
            $rules = [ 'GOOGLE_CLIENT_ID' => 'required', 'GOOGLE_CLIENT_SECRET' => 'required'];
            $checkValidate = validation($input, $rules);
            if($checkValidate['status'] == 0){
                echo json_encode($checkValidate); exit;
            }
        }
        if($type == 'fb' && isset($input['is_fb'])){
            $rules = [ 'FACEBOOK_APP_ID' => 'required', 'FACEBOOK_APP_SECRET' => 'required'];
            $checkValidate = validation($input, $rules);
            if($checkValidate['status'] == 0){
                echo json_encode($checkValidate); exit;
            }
        }
        if($type == 'git' && isset($input['is_github'])){
            $rules = [ 'GITHUB_CLIENT_ID' => 'required', 'GITHUB_CLIENT_SECRET' => 'required'];
            $checkValidate = validation($input, $rules);
            if($checkValidate['status'] == 0){
                echo json_encode($checkValidate); exit;
            }
        }
        if($type == 'twitter' && isset($input['is_twitter'])){
            $rules = [ 'TWITTER_CLIENT_ID' => 'required', 'TWITTER_CLIENT_SECRET' => 'required'];
            $checkValidate = validation($input, $rules);
            if($checkValidate['status'] == 0){
                echo json_encode($checkValidate); exit;
            }
        }
        if($type == 'amazon' && isset($input['is_amazon'])){
            $rules = [ 'AMAZON_CLIENT_ID' => 'required', 'AMAZON_CLIENT_SECRET' => 'required'];
            $checkValidate = validation($input, $rules);
            if($checkValidate['status'] == 0){
                echo json_encode($checkValidate); exit;
            }
        }
        if($type == 'linkedin' && isset($input['is_linkedin'])){
            $rules = [ 'LINKEDIN_CLIENT_ID' => 'required', 'LINKEDIN_CLIENT_SECRET' => 'required'];
            $checkValidate = validation($input, $rules);
            if($checkValidate['status'] == 0){
                echo json_encode($checkValidate); exit;
            }
        }

        if($type == 'newsletter' && isset($input['is_newsletter'])){
            $rules = [ 'MAILCHIMP_APIKEY' => 'required' ];
            $checkValidate = validation($input, $rules);
            if($checkValidate['status'] == 0){
                echo json_encode($checkValidate); exit;
            }
        }

        $appendArr = [];
        if($type == 'google' && isset($input['is_google'])){
            $appendArr = [ 'GOOGLE_CLIENT_ID' => $input['GOOGLE_CLIENT_ID'], 'GOOGLE_CLIENT_SECRET' => $input['GOOGLE_CLIENT_SECRET'], 'is_google'=>1 ];
        }
        if($type == 'fb' && isset($input['is_fb'])){
            $appendArr = [ 'FACEBOOK_APP_ID' => $input['FACEBOOK_APP_ID'],'FACEBOOK_APP_SECRET' => $input['FACEBOOK_APP_SECRET'], 'is_fb'=>1 ];
        }
        if($type == 'git' && isset($input['is_github'])){
            $appendArr = [ 'GITHUB_CLIENT_ID' =>  $input['GITHUB_CLIENT_ID'], 'GITHUB_CLIENT_SECRET' => $input['GITHUB_CLIENT_SECRET'], 'is_github'=>1 ];
        }
        if($type == 'twitter' && isset($input['is_twitter'])){
            $appendArr = [ 'TWITTER_CLIENT_ID' =>  $input['TWITTER_CLIENT_ID'], 'TWITTER_CLIENT_SECRET' => $input['TWITTER_CLIENT_SECRET'], 'is_twitter'=>1 ];
        }
        if($type == 'amazon' && isset($input['is_amazon'])){
            $appendArr = [ 'AMAZON_CLIENT_ID' =>  $input['AMAZON_CLIENT_ID'], 'AMAZON_CLIENT_SECRET' => $input['AMAZON_CLIENT_SECRET'], 'is_amazon'=>1 ];
        }
        if($type == 'linkedin' && isset($input['is_linkedin'])){
            $appendArr = [ 'LINKEDIN_CLIENT_ID' =>  $input['LINKEDIN_CLIENT_ID'], 'LINKEDIN_CLIENT_SECRET' => $input['LINKEDIN_CLIENT_SECRET'], 'is_linkedin'=>1 ];
        }
        if($type == 'newsletter' && isset($input['is_newsletter'])){
            $appendArr = [ 'MAILCHIMP_APIKEY' =>  $input['MAILCHIMP_APIKEY'], 'is_newsletter'=>1 ];
        }
        $success = 0;
        $env_update = $this->changeEnv($appendArr);
        foreach($appendArr as $key=>$val){
            $insert = Settings::updateOrCreate(['name'=>$key],['value'=>$val]);
            $success = 1;
        }
        if($env_update && $success) {
            $resp = array('status'=>1, 'msg'=> __('adminWords.detail').' '.__('adminWords.success_msg'));
        }else {
            $resp = array('status'=>0, 'msg'=> __('adminWords.detail').' '.__('adminWords.could_not_err'));
        }
        echo json_encode($resp);
    }

    public function saveNewsletterApi(Request $request){
        $checkValidate = validation($request->except('_token'), ['MAILCHIMP_APIKEY' => 'required']);
        $env_update = $this->changeEnv([
            'MAILCHIMP_APIKEY' => $request->MAILCHIMP_APIKEY
        ]);
        if($env_update){
            $resp = array('status'=>1, 'msg'=>__('adminWords.api').' '.__('adminWords.updated_msg'));
        }else{
            $resp = array('status'=>0, 'msg'=>__('adminWords.error_msg'));
        }
        echo json_encode($resp);
    }

    public function saveDonationLink(Request $request){
        $checkValidate = validation($request->except('_token'), ['PAYPAL_DONATION_LINK' => 'required']);
        $success = 0;
        if($checkValidate['status'] == 1){
            $env_update = $this->changeEnv(['PAYPAL_DONATION_LINK' => $request->PAYPAL_DONATION_LINK]);
            foreach($request->except('_token') as $key=>$val){
                $insert = Settings::updateOrCreate(['name'=>$key],['value'=>$val]);
                $success = 1;
            }
            if($success && $env_update){
                $resp = array('status'=>1, 'msg'=> __('adminWords.donation').' '.__('adminWords.link').' '.__('adminWords.success_msg'));
            }else{
                $resp = array('status'=>0, 'msg'=>__('adminWords.donation').' '.__('adminWords.link').' '.__('adminWords.could_not_err'));
            }
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

    public function open_exchange(){
        return view('setting::open_exchange');
    }

    public function save_exchange_key(Request $request){
        $checkValidate = validation($request->except('_token'), ['OPEN_EXCHANGE_KEY' => 'required']);
        if($checkValidate['status'] == 1){
            $env_update = $this->changeEnv(['OPEN_EXCHANGE_KEY' => $request->OPEN_EXCHANGE_KEY]);
            $insert = Settings::updateOrCreate(['name'=>'OPEN_EXCHANGE_KEY'],['value'=>$request->OPEN_EXCHANGE_KEY]);
            if($env_update) {
                $resp = array('status'=>1, 'msg'=> __('adminWords.key').' '.__('adminWords.success_msg'));
            }else {
                $resp = array('status'=>0, 'msg'=> __('adminWords.key').' '.__('adminWords.could_not_err'));
            }
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

    // 3rd Party Integration Start
    public function integration(){
        $data['country'] = AllCountry::pluck('nicename', 'iso', 'id');
        return view('setting::integration',$data);
    }

    public function integration_changeStatus(Request $request){  
        $checkValidate = validation($request->except('_token'),['status'=>'required', 'type'=>'required']);        
        if($checkValidate['status'] == 1){
            $update = Settings::updateOrCreate(['name'=>$request->type],['value'=>$request->status]);
            $resp = ['status'=>1,'msg'=>__('adminWords.data').' '.__('adminWords.success_msg')];
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

    public function saveIntegrationData(Request $request, $type){

        $input = $request->except('_token');
        $isChecked = 0;        
        if($type == 'is_s3' && isset($input['is_s3'])){
            $rules = [ 'AWS_DEFAULT_REGION' => 'required', 'AWS_ACCESS_KEY_ID' => 'required','AWS_SECRET_ACCESS_KEY'=> 'required','AWS_BUCKET'=>'required'];

            $checkValidate = validation($input, $rules);
            if($checkValidate['status'] == 0){
                echo json_encode($checkValidate); exit;
            }
        }
        if($type == 'youtube' && isset($input['is_youtube'])){             
            $rules = [ 'YOUTUBE_API_KEY' => 'required'];
            $checkValidate = validation($input, $rules); 
            if($checkValidate['status'] == 0){
                echo json_encode($checkValidate); exit;
            }
        }

        $appendArr = [];
        
        if($type == 'aws_s3' && isset($input['is_s3'])){
            $appendArr = [ 'AWS_DEFAULT_REGION' =>  $input['AWS_DEFAULT_REGION'], 'AWS_ACCESS_KEY_ID' => $input['AWS_ACCESS_KEY_ID'],'AWS_SECRET_ACCESS_KEY'=> $input['AWS_SECRET_ACCESS_KEY'],'AWS_BUCKET'=> $input['AWS_BUCKET'],'artist_upload_on_s3'=> $input['artist_upload_on_s3'],'is_s3'=>1 ];
            if(isset($input['AWS_DIRECTORY']) && !empty($input['AWS_DIRECTORY'])){
                $appendArr['AWS_DIRECTORY'] = $input['AWS_DIRECTORY'];
            }
        }   
        if($type == 'youtube' && isset($input['is_youtube'])){ 
            $appendArr = ['YOUTUBE_API_KEY'=> $input['YOUTUBE_API_KEY'] ,'is_youtube' => 1 ];

            if(isset($input['YOUTUBE_CHANNEL_KEY']) && !empty($input['YOUTUBE_CHANNEL_KEY'])){
                $appendArr['YOUTUBE_CHANNEL_KEY'] = $input['YOUTUBE_CHANNEL_KEY'];
            }else{                
                $appendArr['YOUTUBE_CHANNEL_KEY'] = '';
            }

            if(isset($input['YT_COUNTRY_CODE']) && !empty($input['YT_COUNTRY_CODE'])){
                $appendArr['YT_COUNTRY_CODE'] = $input['YT_COUNTRY_CODE'];
            }else{
                $appendArr['YT_COUNTRY_CODE'] = '';
            }
        }         
        
        $success = 0;
        $env_update = $this->changeEnv($appendArr);
        foreach($appendArr as $key=>$val){
            $insert = Settings::updateOrCreate(['name'=>$key],['value'=>$val]);
            $success = 1;
        }
        if($env_update && $success) {
            $resp = array('status'=>1, 'msg'=> __('adminWords.detail').' '.__('adminWords.success_msg'));
        }else {
            $resp = array('status'=>0, 'msg'=> __('adminWords.detail').' '.__('adminWords.could_not_err'));
        }
        echo json_encode($resp);
    }

    // 3rd Party Integration End
    

    protected function changeEnv($data = array()){
        if(count($data) > 0){
            $env = file_get_contents(base_path() . '/.env');
            $env = preg_split('/\s+/', $env);
            foreach((array)$data as $key => $value){
                foreach($env as $env_key => $env_value){
                    $entry = explode("=", $env_value, 2);
                    if($entry[0] == $key){
                        $env[$env_key] = $key . "=" . str_replace('', "", $value);
                    } else {
                        $env[$env_key] = str_replace('', "", $env_value);
                    }
                }
            }

            $env = implode("\n", $env);
            file_put_contents(base_path() . '/.env', $env);
                
            return true;
        }else{
            return false;
        }
    }
}
