<?php

namespace App\Http\Controllers\Artist;

use App\Http\Controllers\Controller;
use App\ArtistPaymentGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Auth;

class PaymentGatewayController extends Controller
{
    
    public function api(){
        $artistApi = ArtistPaymentGateway::where('user_id',Auth()->user()->id)->first();        
        return view('artist.api')->with('artistApi',$artistApi);
    }

    public function api_update(Request $request, $type){
        
        $input = $request->except('_token');
        $isChecked = 0;
        $appendArr = [];        
        
        if($type == 'razor' && isset($input['is_razorpay'])){
            $rules = [ 'razorpay_key' => 'required', 'razorpay_secret' => 'required' ];
            $checkValidate = validation($input, $rules);
            if($checkValidate['status'] == 0){
                echo json_encode($checkValidate); exit;
            }
            $appendArr = [ 'razorpay_key' => $input['razorpay_key'], 'razorpay_secret' => $input['razorpay_secret'], 'is_razorpay'=>'1' ];            
        }
        if($type == 'paypal' && isset($input['is_paypal'])){
            $rules = [ 'paypal_mode' => 'required', 'paypal_client_id' => 'required', 'paypal_secret' => 'required' ];
            $checkValidate = validation($input, $rules);
            if($checkValidate['status'] == 0){
                echo json_encode($checkValidate); exit;
            }
            $appendArr = [ 'paypal_client_id' => $input['paypal_client_id'],'paypal_secret' => $input['paypal_secret'],'paypal_mode' => $input['paypal_mode'], 'is_paypal'=>'1' ];            
        }        
        if($type == 'stripe' && isset($input['is_stripe'])){
            $rules = [ 'stripe_client_id' => 'required', 'stripe_secret' => 'required'];
            $checkValidate = validation($input, $rules);
            if($checkValidate['status'] == 0){
                echo json_encode($checkValidate); exit;
            }
            $appendArr = [ 'stripe_client_id' =>  $input['stripe_client_id'], 'stripe_secret' => $input['stripe_secret'], 'is_stripe'=>'1' ];            
        } 

        if($type == 'paystack' && isset($input['is_paystack'])){
            $rules = [ 'paystack_public_key' => 'required', 'paystack_secret_key' => 'required', 'paystack_payment_url' => 'required', 'paystack_merchant_email' => 'required'];
            $checkValidate = validation($input, $rules);
            if($checkValidate['status'] == 0){
                echo json_encode($checkValidate); exit;
            }
            $appendArr = [ 'paystack_public_key' =>  $input['paystack_public_key'], 'paystack_secret_key' => $input['paystack_secret_key'],'paystack_payment_url' =>  $input['paystack_payment_url'], 'paystack_merchant_email' => $input['paystack_merchant_email'], 'is_paystack'=>'1' ];            
        }   

        if(isset($input['default_pay_gateway']) && !empty($input['default_pay_gateway'])){
            $appendArr = array_add($appendArr, 'default_pay_gateway', $input['default_pay_gateway']);
        }          
        
        $addAndUpdate = ArtistPaymentGateway::updateOrCreate(
            ['user_id' =>  Auth()->user()->id],
            $appendArr
        );
        if($addAndUpdate) {
            $resp = array('status'=>1, 'msg'=>__('adminWords.settings').' '.__('adminWords.success_msg'));
         }else {
             $resp = array('status'=>0, 'msg'=>__('adminWords.settings').' '.__('adminWords.could_not_err'));
         }
        echo json_encode($resp);
        
    } 

    public function updateStatus(Request $request){  
        $checkValidate = validation($request->except('_token'),['status'=>'required', 'type'=>'required']);
        if($checkValidate['status'] == 1){
            $addAndUpdate = ArtistPaymentGateway::updateOrCreate(
                ['user_id' =>  Auth()->user()->id],
                [$request->type => $request->status]
            );
            $resp = ['status'=>1,'msg'=>__('adminWords.data').' '.__('adminWords.success_msg')];
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

}
