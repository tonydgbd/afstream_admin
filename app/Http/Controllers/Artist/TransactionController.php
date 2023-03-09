<?php

namespace App\Http\Controllers\Artist;

use Illuminate\Http\Request;
use Modules\Setting\Entities\Settings;
use Modules\Setting\Entities\Currency;
use PayPal\Auth\OAuthTokenCredential;
use App\Http\Controllers\Controller;
use App\Notifications\PaymentNotify;
use Modules\Coupon\Entities\Coupon;
use Modules\Audio\Entities\Audio;
use Tzsk\Payu\Facade\PayuPayment;
use PayPal\Api\PaymentExecution;
use Modules\Plan\Entities\Plan;
use App\Helpers\currencyRate;
use PayPal\Api\ExecutePayment;
use App\SuccessPayment;
use PayPal\Api\RedirectUrls;
use PayPal\Rest\ApiContext;
use App\UserPurchasedPlan;
use App\CouponManagement;
use PayPal\Api\Transaction;
use PayPal\Api\ItemList; 
use App\paymentGateway; 
use PayPal\Api\Payment;
use PayPal\Api\Details;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use Razorpay\Api\Api;
use PayPal\Api\Item;
use App\AdminAudioPayment;
use PaytmWallet;
use Validator;
use Braintree;
use Redirect;
use Session;
use Stripe;
use Alert;
use Crypt;
use Auth;
use URL;
use Str;
use Paystack;
use App\ArtistAudioPayment;
use App\User;
use App\UserAction;
use DB;


class TransactionController extends Controller{
    
    private $_api_context;
    
    public function __construct(){
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    public function postPaymentWithpaypal(Request $request){
        
        if(isset(Auth::user()->id)){
            
            if(isset($request->audio_id) && !empty($request->audio_id)){

                $checkAudio = Audio::find($request->audio_id);                                              
                
                $defaultCode = getDefaultCurrency($code = true);
                if(isset($defaultCode) && !empty($defaultCode) && $defaultCode != 'USD'){
                    $payout = (float) currency($checkAudio->download_price, $defaultCode, 'USD'); 
                }else{
                    $payout = $checkAudio->download_price;
                }

                if(!empty($payout) && $payout <= 0){
                    alert()->error( __('frontWords.insufficient_amount_for_transaction'))->persistent("Close");  
                    return Redirect::back();
                }
                
                $setcurrency = 'USD'; 
                $payer = new Payer();
                $payer->setPaymentMethod('paypal');
                $item_1 = new Item();
                $item_1->setName($checkAudio->audio_title)
                    ->setCurrency($setcurrency)->setQuantity(1)
                    ->setPrice($payout);
                $item_list = new ItemList();
                $item_list->setItems(array(
                    $item_1,
                ));
                $amount = new Amount();
                $amount->setCurrency($setcurrency)->setTotal($payout);
                $transaction = new Transaction();
                $transaction->setAmount($amount)->setItemList($item_list)->setDescription('Payment for '.$checkAudio->audio_title.' plan');
                $redirect_urls = new RedirectUrls();
                $redirect_urls->setReturnUrl(url('/paypal/paymentStatusSingleAudio'))
                    ->setCancelUrl(URL::to('/checkout/buySingleAudio'));
                $payment = new Payment();
                $payment->setIntent('Sale')
                    ->setPayer($payer)->setRedirectUrls($redirect_urls)->setTransactions(array(
                    $transaction,
                ));

                try{
                    $payment->create($this->_api_context);
                } catch (\PayPal\Exception\PPConnectionException $ex) {
                    if (\Config::get('app.debug')) {
                        return 'failed';
                    } else {
                        return 'fail';
                    }
                }
                
                foreach ($payment->getLinks() as $link) {
                    if ($link->getRel() == 'approval_url') {
                        $redirect_url = $link->getHref();
                        break;
                    }
                }                
                
                Session::put('paypal_payment_id', $payment->getId());
                Session::put('custom_id', Auth::user()->id);
                Session::put('purchase_audio_id', $request->audio_id);
                Session::put('amount_currency', $request->amount.'-'.$setcurrency);        

                if (isset($redirect_url)) {
                    return Redirect::away($redirect_url);
                }
            }else{
                alert()->error( __('frontWords.try_again'))->persistent("Close");  
                return Redirect::back();
            }
        }else{
            alert()->error( __('frontWords.login_err'))->persistent("Close");  
            return Redirect::back();
        }
    }


    public function getPaymentStatus(Request $request){

        $home = getSelectedHomepage();
        $payment_id = Session::get('paypal_payment_id');
        $audioId = Session::get('purchase_audio_id'); 
        Session::forget('paypal_payment_id');
        $req = $request->all();
        

        if (empty($req['PayerID']) || empty($req['token'])) {
            alert()->error( __('frontWords.payment_fail'))->persistent("Close");  
            return Redirect::back();
        }
        
        $payment = Payment::get($payment_id, $this->_api_context);
      
        $execution = new PaymentExecution();
        $execution->setPayerId($req['PayerID']);
        $result = $payment->execute($execution, $this->_api_context);

        $audioDetail = Audio::where('id',$audioId)->get()->toArray();       
        $audioData = (object)$audioDetail[0];
        
        $defaultSymbol = getDefaultCurrency();
        $defaultCode = getDefaultCurrency($code = true);
        
        if(isset($defaultCode) && !empty($defaultCode) && $defaultCode != 'USD'){
            $amount = currency($result->transactions[0]->amount->total, 'USD', $defaultCode); 
            $amount = str_replace(",", "", $amount);
        }else{
            $amount = $result->transactions[0]->amount->total;
        }
        $respObj = (object)['transaction_id' => $result->id, 'payment_id' => $payment_id, 'amount' => $audioDetail[0]['download_price'], 'payment_gateway' => 'paypal', 'order_id' => uniqid(), 'currency' => $defaultSymbol, 'user_name' => auth()->user()->name, 'user_email' => auth()->user()->email ];                
        

        if ($result->getState() == 'approved') {             
            /** Here Write your database logic like that insert record or value in database if you want **/
            $getResp = $this->saveAudioPaymentData([ 'user_id' => Auth::user()->id, 'artist_id' => $audioDetail[0]['user_id'], 'audio_id' => $audioId, 'audio_data' => $audioData, 'respObj' => $respObj, 'payment_gateway' => 'paypal' ,'amount' => $audioDetail[0]['download_price'], 'currency' => $defaultSymbol,'status' => '1']); 
            alert()->success( __('frontWords.txn_id').' : '.$result->id, __('frontWords.payment_done'))->persistent("Close");    
            return redirect()->back();
        }else{
            $getResp = $this->saveAudioPaymentData([ 'user_id' => Auth::user()->id, 'artist_id' => $audioDetail[0]['user_id'], 'audio_id' => $audioId, 'audio_data' => $audioData, 'respObj' => $respObj, 'payment_gateway' => 'paypal','amount' => $audioDetail[0]['download_price'] ,'currency' => $defaultSymbol,'status' => '0']);             
        }
        Session::forget(['plan_id', 'custom_id', 'discount_and_amount']);
        alert()->error( __('frontWords.payment_fail'))->persistent("Close");  
        return redirect()->back();
    }

    public function paypalCancelReturn(Request $request){
        $explodeAmnt = explode('-',Session::get('discount_amount_and_tax'));
        $setcurrency = session()->get('currency')['code'];
        $explodeAmntCurr = explode('-', Session::get('amount_currency'));
        $addPayment = paymentGateway::create([ 'user_id' => Auth::user()->id, 'order_id' => uniqid(), 'plan_id' => Session::get('plan_id'), 'payment_data' => json_encode([(object)['transaction_id' => $request->token, 'amount' => $explodeAmntCurr[0], 'payment_gateway' => 'paypal', 'order_id' => uniqid(), 'discount' => $explodeAmnt[0], 'plan_exact_amount' => $explodeAmnt[1], 'taxPercent' => $explodeAmnt[2], 'taxAmount' => $explodeAmnt[3], 'currency' => getCurrency(['curr_code' => $explodeAmntCurr[1]]) ]]), 'type' => 'paypal', 'status' => 0 ]);
        alert()->error( __('frontWords.payment_fail'))->persistent("Close");  
        return Redirect::back();
    }
    
    public function stripe(){
        return view('stripe');
    }

    public function stripePayment(Request $request){
        
        if(isset(Auth::user()->id)){
            $checkValidate = validation($request->all(), [
                'number' => 'required',
                'name' => 'required',
                'expiry' => 'required',
                'cvc' => 'required|max:3',
                'audio_id' => 'required',
            ]);
            
            if($checkValidate['status'] == 1){

                try{

                    $audioDetail = Audio::where('id',$request->audio_id)->get()->toArray();
                    $audioData = (object)$audioDetail[0];
                    $input = $request->except('_token');
                    $stripe = Stripe::make(env('STRIPE_SECRET'));
                    if($stripe == '' || $stripe == null){
                        return json_encode(['status'=>0, 'msg'=> __('frontWords.stripe_err'), 'swal' => 1]);
                    }
                    $monthYear = explode('/',$request->expiry);
                    $token = $stripe->tokens()->create([
                        'card' => [
                            'number' => $request->number,
                            'exp_month' => (int)$monthYear[0],
                            'exp_year' => (int)$monthYear[1],
                            'cvc' => $request->cvc,
                        ]
                    ]);
                    if(!isset($token['id'])){
                        return json_encode(['status'=> 0, 'msg'=>__('frontWords.try_again'), 'swal' => 0]);
                    }
                    
                    $defaultCode = getDefaultCurrency($code = true);
                    $defaultSymbol = getDefaultCurrency();
                    
                    if(isset($defaultCode) && !empty($defaultCode) && $defaultCode != 'USD'){
                        $payAmount = (float) currency($audioDetail[0]['download_price'], $defaultCode, 'USD'); 
                    }else{
                        $payAmount = $audioDetail[0]['download_price'];
                    }
                    
                    $charge = $stripe->charges()->create([
                        'card' => $token['id'],
                        'currency' => 'USD', //session()->get('currency')['code'],
                        'amount' => $payAmount, //$audioDetail[0]['download_price'],
                        'description' => isset($settings->w_title) ? $settings->w_title : __('frontWords.site_title'),
                    ]);                    

                    $respObj = (object)['transaction_id' =>$charge['balance_transaction'], 'amount' => $audioDetail[0]['download_price'], 'payment_gateway' => 'stripe', 'order_id' => uniqid(), 'currency' => $defaultSymbol, 'user_name' => auth()->user()->name, 'user_email' => auth()->user()->email ];   

                    if($charge['status'] == 'succeeded'){                      
                        
                        $getResp = $this->saveAudioPaymentData([ 'user_id' => Auth::user()->id, 'artist_id' => $audioDetail[0]['user_id'], 'audio_id' => $request->audio_id, 'audio_data' => $audioData, 'respObj' => $respObj, 'payment_gateway' => 'stripe' ,'amount' => $audioDetail[0]['download_price'],'currency' => $defaultSymbol, 'status' => '1']); 
                       
                        if($getResp){                            
                            $resp = ['status'=>1, 'msg'=> __('frontWords.txn_id').' : '.$charge['balance_transaction'].' '. __('frontWords.payment_done'), 'swal' => 1 ];
                        }else{
                            $resp = ['status'=>0, 'msg'=> __('frontWords.try_again'), 'swal' => 1 ];
                        }
                    }else{
                        $getResp = $this->saveAudioPaymentData([ 'user_id' => Auth::user()->id, 'artist_id' => $audioDetail[0]['user_id'], 'audio_id' => $request->audio_id, 'audio_data' => $audioData, 'respObj' => $respObj, 'payment_gateway' => 'stripe' ,'amount' => $audioDetail[0]['download_price'],'currency' => $defaultSymbol, 'status' => '0']);                        
                        $resp = ['status'=>0, 'msg'=> __('frontWords.try_again'), 'swal' => 1 ];
                    }
                }catch(\Exception $e){
                    $resp = ['status'=>0, 'msg'=> $e->getMessage(), 'swal' => 1 ];
                    echo json_encode($resp); die;
                }
            }else{
                $resp = $checkValidate;
            }
        }else{
            alert()->error( __('frontWords.login_err'))->persistent("Close");  
            return Redirect::back();
        }
        echo json_encode($resp);
    } 


    public function braintree(){
        return view('braintree');
    }

    public function accesstoken(){
        $gateway = $this->brainConfig();
        $clientToken = $gateway->clientToken()->generate();
        return response()->json(array('client' => $clientToken));
    } 

     /* Config function to get the braintree config data to process all the apis on braintree gateway */
     public function brainConfig(){
        return $gateway = new Braintree\Gateway([
             'environment' => env('BRAINTREE_ENV'),
             'merchantId' => env('BRAINTREE_MERCHANT_ID'),
             'publicKey' => env('BRAINTREE_PUBLIC_KEY'),
             'privateKey' => env('BRAINTREE_PRIVATE_KEY'),
        ]);
    }

    public function successBraintree(Request $request){
        if(isset(Auth::user()->id)){
            $checkPlan = Plan::where(['id' => $request->plan_id])->get();
            if(sizeof($checkPlan) > 0){
                try{
                    $gateway = $this->brainConfig();
                    $response = $gateway->transaction()->sale([
                        'amount' => $request->amount,
                        'paymentMethodNonce' => $request->payment_method_nonce,
                        'customerId' => $this->createCustomer(),
                        'options' => [
                            'submitForSettlement' => true,
                        ],
                    ]);
                    $resp = $response->transaction;
                    if(!empty($resp) && $resp != 'null'){
                        $errResp = (object)['transaction_id' => $resp->id, 'amount' => $resp->amount, 'payment_gateway' => 'braintree', 'currency' => getCurrency(['curr_code' => $resp->currencyIsoCode]), 'order_id' => uniqid(), 'discount' => $request->discountApplied, 'plan_exact_amount' => $request->planExactAmnt, 'taxPercent' => $request->taxPercent, 'taxAmount' => $request->taxApplied, 'user_name' => auth()->user()->name, 'user_email' => auth()->user()->email ];

                        if ($response->success == true) {
                            if(Session::get('coupon_id') != ''){
                                $this->checkAppliedCoupon(Session::get('coupon_id'));
                                Session::forget('coupon_id');
                            }
                            
                            $getResp = $this->savePaymentData([ 'user_id' => Auth::user()->id, 'plan_id' => $request->plan_id, 'respObj' => $errResp, 'type' => 'braintree' ]);
                            alert()->success( __('frontWords.txn_id').' : '.$resp->id, __('frontWords.payment_done'))->persistent("Close");    
                            return redirect('/');
                        }else{
                            $success = paymentGateway::create(['user_id' => Auth::user()->id, 'plan_id' => $request->plan_id, 'payment_data' => json_encode([$errResp]), 'type' => 'braintree', 'payment_gateway' => 'braintree', 'status' => 0, 'order_id' => uniqid()]);
                            alert()->error( __('frontWords.payment_fail'))->persistent("Close");  
                            return Redirect::back();
                        }  
                    }  else{
                        alert()->error( __('frontWords.try_again'))->persistent("Close");  
                        return Redirect::back();
                    }   
                }catch(\Exception $e){
                    alert()->error($e->getMessage())->persistent("Close");  
                    return Redirect::back();
                }    
            }else{
                alert()->error( __('frontWords.try_again'))->persistent("Close");  
                return Redirect::back();
            }
            alert()->error( __('frontWords.try_again'))->persistent("Close");  
            return Redirect::back();
        }else{
            alert()->error( __('frontWords.login_err'))->persistent("Close");  
            return Redirect::back();
        }
    }

    public function createCustomer(){
        if(!Auth::user()->braintree_id) {
            $gateway = $this->brainConfig();
            $result = $gateway->customer()->create([
                'firstName' => Auth::user()->name,
                'email' => Auth::user()->email,
                ]);
               
            if ($result->success) {
                User::where('id', Auth::user()->id)->update(['braintree_id' => $result->customer->id]);
                return $result->customer->id;
            }
        }else{
            return Auth::user()->braintree_id;
        }
    }

    public function instamojo(){
        return view('instamojo');
    }

    public function payWithIM(Request $request){
        if(isset(Auth::user()->id)){
            $api = new \Instamojo\Instamojo(
                env('IM_API_KEY'),
                env('IM_AUTH_TOKEN'),
                env('IM_URL')
            );
            try {
                $response = $api->paymentRequestCreate(array(
                    "purpose" => isset($settings->w_title) ? $settings->w_title : __('frontWords.site_title'),
                    "amount" => $request->amount,
                    "buyer_name" => Auth::user()->name.'-'.$request->plan_id.'-'.$request->discountApplied.'-'.$request->planExactAmnt.'-'.$request->taxPercent.'-'. $request->taxApplied,
                    "send_email" => true,
                    "email" => Auth::user()->email,
                    "phone" => Auth::user()->mobile,
                    "redirect_url" => route('instamojo.success')
                ));
                    
                    header('Location: ' . $response['longurl']);
                    exit();
            }catch (\Exception $e) {
                alert()->error($e->getMessage())->persistent("Close");  
                return Redirect::back();
            }
        }else{
            alert()->error( __('frontWords.login_err'))->persistent("Close");  
            return Redirect::back();
        }
    }
    
    public function success(Request $request){
        try {
            $api = new \Instamojo\Instamojo(
                env('IM_API_KEY'),
                env('IM_AUTH_TOKEN'),
                env('IM_URL')
            );
    
            $response = $api->paymentRequestStatus(request('payment_request_id'));

            $explode_arr = explode('-',$response['buyer_name']);

            $respObj = (object)[ 'transaction_id' => $response['id'], 'amount' => $response['amount'], 'currency' => getCurrency(['curr_code' => session()->get('currency')['code']]), 'phone' => $response['phone'], 'email' => $response['email'], 'buyer_name' => $explode_arr[0], 'order_id' => uniqid(), 'discount' => $explode_arr[2], 'plan_exact_amount' => $explode_arr[3], 'taxPercent' => $explode_arr[4], 'taxAmount' => $explode_arr[5], 'payment_gateway' => 'instamojo', 'user_name' => auth()->user()->name, 'user_email' => auth()->user()->email ];

            if( !isset($response['payments'][0]['status']) ) {
                $success = paymentGateway::create([ 'user_id' => Auth::user()->id, 'plan_id' => $explode_arr[1], 'payment_data' => json_encode([$respObj]), 'type' => 'instamojo', 'status' =>0, 'order_id' => uniqid() ]);
                alert()->error( __('frontWords.payment_fail'))->persistent("Close");  
                return Redirect::back();
            } else if($response['payments'][0]['status'] != 'Credit') {
                $success = paymentGateway::create([ 'user_id' => Auth::user()->id, 'plan_id' => $explode_arr[1], 'payment_data' => json_encode([$respObj]), 'type' => 'instamojo', 'status' =>0, 'order_id' => uniqid() ]);
                alert()->error( __('frontWords.payment_fail'))->persistent("Close");  
                return Redirect::back();
            } 
        }catch (\Exception $e) {
            $success = paymentGateway::create([ 'user_id' => Auth::user()->id, 'plan_id' => $explode_arr[1], 'payment_data' => json_encode([$respObj]), 'type' => 'instamojo', 'status' =>0, 'order_id' => uniqid() ]);
            alert()->error($e->getMessage())->persistent("Close");  
            return Redirect::back();
        }
       
        
        if(isset($response['id']) && $response['status'] == 'Completed'){
            if(Session::get('coupon_id') != ''){
                $this->checkAppliedCoupon(Session::get('coupon_id'));
                Session::forget('coupon_id');
            }
           
            $success = $this->savePaymentData([ 'user_id' => Auth::user()->id, 'plan_id' => $explode_arr[1], 'respObj' => $respObj, 'type' => 'instamojo', 'payment_gateway' => 'instamojo', 'status' =>1 ]);
        }else{
            $success = paymentGateway::create([ 'user_id' => Auth::user()->id, 'plan_id' => $explode_arr[1], 'payment_data' => json_encode($respObj), 'type' => 'instamojo', 'status' =>0, 'order_id' => uniqid() ]);
        }
        if($success){
            alert()->success( __('frontWords.txn_id').' : '.$response['id'], __('frontWords.payment_done'))->persistent("Close");    
            return redirect('/');            
        }else{
            alert()->error( __('frontWords.something_wrong'))->persistent("Close");  
        }
        return Redirect::back();
    }

    
    public function paystack(){
        return view('paystack');
    }

    public function redirectToGateway(Request $request){
        if(isset(Auth::user()->id)){
            $checkValidate = validation($request->except('_token'), ['audio_id' => 'required']);
            if($checkValidate['status'] == 1){
                
                $checkAudio = Audio::find($request->audio_id);                               
                if(!empty($checkAudio) && isset($checkAudio->download_price) && !empty($checkAudio->download_price)){                   
                   
                    $title = Settings::where('name', 'w_title')->first();
                    $logo = Settings::where('name', 'mini_logo')->first();                        
                    $data = [
                        'reference' => Paystack::genTranxRef(), 
                        'paystack_key' => env('PAYSTACK_PUBLIC_KEY'),
                        'amount' => $checkAudio->download_price*100,
                        'name' => isset($title->value) ? $title->value : __('frontWords.site_title'), 
                        'description' => $checkAudio->audio_title, 
                        'image' => (isset($logo->value) ? asset('public/images/sites/'.$logo->value) : asset('public/images/sites/mini_logo.webp')), 
                        "color" => "#3399cc", 
                        "email" => (isset(Auth::user()->email) ? Auth::user()->email : ''),
                        "user_name" => (isset(Auth::user()->name) ? Auth::user()->name : ''), 
                        "contact" => (Auth::user() != '' ? Auth::user()->mobile : '')
                    ];                    
                    $resp = ['status' => 1, 'data' => $data];
                }else{
                    $resp = ['status' => 0, 'msg' => __('frontWords.something_wrong')];
                }
            }else{
                $resp = $checkValidate;
            }
        }else{
            $resp = ['status' => 0, 'msg' => __('frontWords.login_err')];
        }
        echo json_encode($resp);
    }

    public function handleGatewayCallback(Request $request){

        if(isset(Auth::user()->id)){

            $input = $request->all();
            
            if(count($input)  && !empty($input['transaction'])) {
                
                $audioDetail = Audio::where('id',$input['audio_id'])->get()->toArray();
                $audioData = (object)$audioDetail[0];
                
                $respObj = (object)['transaction_id' => $input['transaction'], 'payment_id' => $input['reference'], 'amount' => round($audioDetail[0]['download_price']/100, 2), 'payment_gateway' => 'paystack', 'order_id' => uniqid(), 'currency' => getCurrency(['curr_code' => $input['currency'] ]), 'user_name' => auth()->user()->name, 'user_email' => auth()->user()->email ];                
                
                if($input['status'] == 'success'){
                    $getResp = $this->saveAudioPaymentData([ 'user_id' => Auth::user()->id, 'artist_id' => $audioDetail[0]['user_id'], 'audio_id' => $input['audio_id'], 'audio_data' => $audioData, 'respObj' => $respObj, 'payment_gateway' => 'paystack', 'amount' => round($audioDetail[0]['download_price']/100, 2) ,'currency' => getCurrency(['curr_code' => $input['currency'] ]),'status' => '1']);   
                        echo json_encode(['status' => 1, 'msg' => __('frontWords.txn_id').' : '.$input['transaction'].'<br>'.__('frontWords.payment_done')]);
                }else{
                    $getResp = $this->saveAudioPaymentData([ 'user_id' => Auth::user()->id, 'artist_id' => $audioDetail[0]['user_id'], 'audio_id' => $input['audio_id'], 'audio_data' => $audioData, 'respObj' => $respObj, 'payment_gateway' => 'paystack' ,'amount' => round($audioDetail[0]['download_price']/100, 2),'currency' => getCurrency(['curr_code' => $input['currency'] ]),'status' => '0']);   
                        echo json_encode(['status' => 0, 'msg' => __('frontWords.txn_id').' : '.$input['transaction'].'<br>'.__('frontWords.payment_fail')]);
                }             

            }else{
                echo json_encode(['status' => 0, 'msg' => __('frontWords.something_wrong')]);
            }
        }else{
            echo json_encode(['status' => 0, 'msg' => __('frontWords.login_err')]);
        }
    }


    public function payu(){
        return view('payu');
    }

    public function payWithPayu(Request $request){
        $data = [
            'txnid' => uniqid(), # Transaction ID.
            'amount' => $request->amount, # Amount to be charged.
            'productinfo' => $request->productinfo,
            'firstname' => Auth::user()->name,
            'email' => Auth::user()->email,
            'phone' => Auth::user()->mobile, # Payee Phone Number.
            'udf1' =>Auth::user()->id.'-'.$request->discountApplied.'-'.$request->planExactAmnt.'-'.$request->taxPercent.'-'. $request->taxApplied,
            'udf2' => $request->plan_id,
            'surl' => url('/payUstatus'),
            'furl' => url('/payUstatus')
        ];
        
        try{
            return PayuPayment::make($data, function($then) {
                $then->redirectRoute('payUstatus'); # Your Status Route.
            });
        }catch(\Exception $e){
            alert()->error( $e->getMessage())->persistent("Close"); 
            return Redirect::back();
        }
        
    }

    public function payUstatus() {
        $payment = PayuPayment::capture(); # Recieve the payment.
        # Returns PayuPayment Instance.
        $payment->getData(); # Get the full response from Gateway.
        
        $payment->isCaptured(); # Is the payment captured or some internal failure occured.
        $data = $payment->getData();
        $explodeArr = explode('-',$data->udf1);
        
        if($payment->status == 'Completed' && $payment->isCaptured()){
            if(Session::get('coupon_id') != ''){
                $this->checkAppliedCoupon(Session::get('coupon_id'));
                Session::forget('coupon_id');
            }
            $respObj = (object)[ 'transaction_id' => $data->txnid, 'amount' => $data->net_amount_debit, 'currency' => getCurrency(['curr_code' => session()->get('currency')['code'] ]), 'order_id' => uniqid(),  'discount' => $explodeArr[1], 'plan_exact_amount' => $explodeArr[2], 'taxPercent' => $explodeArr[3], 'taxAmount' => $explodeArr[4], 'payment_gateway' => 'payu', 'user_name' => auth()->user()->name, 'user_email' => auth()->user()->email ];

            $getResp = $this->savePaymentData([ 'user_id' => $explodeArr[0], 'plan_id' => $data->udf2, 'respObj' => $respObj, 'type' => 'payumoney', 'payment_gateway' => 'payumoney', 'status' =>1 ]);

            alert()->success( __('frontWords.txn_id').' : '.$data->txnid, __('frontWords.payment_done'))->persistent("Close");    
            return redirect('/');
        }else{
            $respObj = (object)[ 'transaction_id' => $data->txnid, 'amount' => $data->amount, 'currency' => getCurrency(['curr_code' => session()->get('currency')['code'] ]), 'order_id' => uniqid(),  'discount' => $explodeArr[1], 'plan_exact_amount' => $explodeArr[2], 'taxPercent' => $explodeArr[3], 'taxAmount' => $explodeArr[4], 'payment_gateway' => 'payu' ];

            $inserted = paymentGateway::create([ 'user_id' => $explodeArr[0], 'plan_id' => $data->udf2, 'payment_data' => json_encode([$respObj]), 'type' => 'payumoney', 'payment_gateway' => 'payumoney', 'status' =>0, 'order_id' => uniqid() ]);
            alert()->error( __('frontWords.payment_fail'))->persistent("Close");  
        }       

        return Redirect::back();
    }

    public function order(Request $request){
        if(isset(Auth::user()->id)){
            try{
                $payment = PaytmWallet::with('receive');
                $payment->prepare([
                    'order' => uniqid().'-'.Auth::user()->id.'-'.$request->plan_id.'-'.$request->discountApplied.'-'.$request->planExactAmnt.'-'.$request->taxPercent.'-'. $request->taxApplied,
                    'user' => Auth::user()->id,
                    'email' => Auth::user()->email,
                    'mobile_number' => Auth::user()->mobile,
                    'amount' => $request->amount,
                    'callback_url' => url('payment/status')
                    ]);
                return $payment->receive();
            }catch(\Exception $e){
                alert()->error( $e->getMessage())->persistent("Close");      
                return Redirect::back();
            }
        }else{
            alert()->error( __('frontWords.payment_fail'))->persistent("Close");  
            return Redirect::back();
        }
    }

    
    public function paymentCallback(Request $request){

        $transaction = PaytmWallet::with('receive');
        $response = $transaction->response();
        $arr = explode('-',$response['ORDERID']);
        
        $respObj = (object)[ 'transaction_id' => $response['TXNID'], 'amount' => $response['TXNAMOUNT'], 'currency' => getCurrency(['curr_code' => $response['CURRENCY'] ]), 'order_id' => uniqid(), 'discount' => $arr[3], 'plan_exact_amount' => $arr[4],  'taxPercent' => $arr[5], 'taxAmount' => $arr[6], 'payment_gateway' => 'paytm', 'user_name' => auth()->user()->name, 'user_email' => auth()->user()->email ];
        
        if($transaction->isSuccessful()){
            if(Session::get('coupon_id') != ''){
                $this->checkAppliedCoupon(Session::get('coupon_id'));
                Session::forget('coupon_id');
            }
            $getResp = $this->savePaymentData([ 'user_id' => $arr[1], 'plan_id' => $arr[2], 'respObj' => $respObj, 'type' => 'paytm', 'payment_gateway' => 'paytm' ]);

            if($getResp){
                alert()->success( __('frontWords.txn_id').' : '.$response['TXNID'], __('frontWords.payment_done'))->persistent("Close");    
                return redirect('/');
            }else{
                alert()->error( __('frontWords.try_again'))->persistent("Close");  
                return Redirect::back();
            }
        }else if($transaction->isFailed()){
            $inserted = paymentGateway::create([ 'user_id' => $arr[1], 'plan_id' => $arr[2], 'payment_data' => json_encode([$respObj]), 'type' => 'paytm', 'payment_gateway' => 'paytm', 'status' =>0, 'order_id' => uniqid() ]);
            alert()->error( $response['RESPMSG'] )->persistent("Close");  
            return Redirect::back();
        }else if($transaction->isOpen()){
            dd($transaction);
        }
    }    

    public function checkAppliedCoupon($coupon_id){
        $addUpdate = CouponManagement::updateOrCreate(['user_id' => Auth::user()->id, 'coupon_id' => $coupon_id], ['coupon_used_count'=> DB::raw('coupon_used_count+1')] );
    }


    function savePaymentData($param){

            $checkUser = SuccessPayment::where('user_id', $param['user_id'])->get();

            $paymentObj[] = $param['respObj'];
            
            $sendData =  [
                'user_id' => $param['user_id'],
                'type' => $param['type'],
                'status' => 1,
                'plan_id' => $param['plan_id'],
                'payment_data' => json_encode($paymentObj),
                'order_id' => $param['respObj']->order_id
            ];
            
            $addUpdate = SuccessPayment::create($sendData);

            if(!isset($param['manual_pay'])){
                $addPayment = paymentGateway::create($sendData);
            }else{
                $addPayment = 1;
            }
             
            if(!empty(env('MAIL_PASSWORD'))){
                $this->paymentNotify(['amount' => $param['respObj']->currency.$param['respObj']->amount, 'txn_id' => $param['respObj']->transaction_id]);
            }
       
        $purchased_plan_date = date("Y-m-d", strtotime(date('Y-m-d')));

        $updatePlan = User::where('id', $param['user_id'])->update(['plan_id' => $param['plan_id'],'purchased_plan_date' => $purchased_plan_date]);
        $getPlan = Plan::find($param['plan_id']);
        
        if(!empty($getPlan)){
            $isDayMonth = $getPlan->is_month_days;
            $daysMon = ($isDayMonth == 0) ? 'day' : 'month';
            $planValid = $getPlan->validity;
            $expiry_date = date("Y-m-d", strtotime("+".$planValid.' '.$daysMon, strtotime(date('Y-m-d'))));

            $addPlanDetail = UserPurchasedPlan::create([
                'user_id' => $param['user_id'],
                'plan_id' => $param['plan_id'],
                'order_id' => $param['respObj']->order_id,
                'plan_data' => json_encode($getPlan),
                'payment_data' => json_encode($paymentObj),
                'currency' => $param['respObj']->currency,
                'expiry_date' => $expiry_date
            ]); 
        } 
        
        return ($addPayment ? 1 : 0); 
    }
   

    public function payWithManualPay(Request $request){

        $checkValidate = validation($request->except('_token'), ['payment_proof' => 'required|mimes:jpg,jpeg,png|max:2048'] );
        if($checkValidate['status'] == 1 && $request->plan_id != '' && $request->amount != ''){
            $paymentProof = '';
            if($image = $request->file('payment_proof')){
                $name = 'payment-'.time().'.'.$image->getClientOriginalExtension();
                $paymentProof = str_replace(' ','',$name);
                upload_image($image, public_path().'/images/payment/', $paymentProof);
            }
            if(Session::get('coupon_id') != ''){
                $this->checkAppliedCoupon(Session::get('coupon_id'));
                Session::forget('coupon_id');
            }
            $paymentObj[] = (object)[ 'user_name' => Auth::user()->name, 'transaction_id' => uniqid(), 'amount' => $request->amount, 'currency' => getCurrency([ 'curr_code' => $request->currency ]), 'payment_gateway' => 'manual_pay', 'payment_proof_doc' => $paymentProof, 'order_id' => uniqid(), 'discount' => $request->discountApplied, 'plan_exact_amount' => $request->planExactAmnt, 'taxPercent' => $request->taxPercent, 'taxAmount' => $request->taxApplied ];
            $sendData =  [
                'user_id' => Auth::user()->id,
                'type' => 'manual_pay',
                'status' => 2,
                'plan_id' => $request->plan_id,
                'order_id' =>$paymentObj[0]->order_id,
                'payment_data' => json_encode($paymentObj)
            ];
            $addPayment = paymentGateway::create($sendData);
            $resp = ['status' => 1, 'msg' => __('adminWords.data').' '.__('adminWords.success_msg')];
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

    public function payment_status(Request $request){
        $checkValidate = validation($request->except('_token'), ['status' => 'required', 'payment_id' => 'required']);
        if($checkValidate['status'] == 1){
            $payment = paymentGateway::find($request->payment_id);
            if(!empty($payment)){
                $updateStatus = $payment->update(['status' => $request->status]);
                if($updateStatus && $request->status == 1){
                    $getResp = $this->savePaymentData([ 'manual_pay' => 1, 'user_id' => $payment->user_id, 'plan_id' => $payment->plan_id, 'respObj' => json_decode($payment->payment_data)[0], 'order_id' => $payment->order_id, 'type' => 'manual_pay', 'payment_gateway' => 'manual_pay', 'status' =>1 ]);
                }
                $resp = ['status' => 1, 'msg' => __('adminWords.status').' '.__('adminWords.updated_msg')];
            }
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }



    public function razorpayBuySingleAudioFormRender(Request $request){
        if(isset(Auth::user()->id)){
            $checkValidate = validation($request->except('_token'), ['audio_id' => 'required']);
            if($checkValidate['status'] == 1){
                
                $checkAudio = Audio::find($request->audio_id);                               
                if(!empty($checkAudio) && isset($checkAudio->download_price) && !empty($checkAudio->download_price)){                   
                   
                    $title = Settings::where('name', 'w_title')->first();
                    $logo = Settings::where('name', 'mini_logo')->first();  
                    
                    $defaultCode = getDefaultCurrency($code = true);
                    if(isset($defaultCode) && !empty($defaultCode) && $defaultCode != 'USD'){
                        $payout = (float) currency($checkAudio->download_price, $defaultCode, 'USD'); 
                    }else{
                        $payout = $checkAudio->download_price;
                    }
    
                    if(!empty($payout) && $payout <= 0){
                        $resp = ['status' => 0, 'msg' => __('frontWords.insufficient_amount_for_transaction')]; die;
                    }
                
                    $data = [
                        'razorpay_key' => env('RAZORPAY_KEY'),
                        'amount' => $payout*100,
                        'currency' => 'USD', 
                        'name' => isset($title->value) ? $title->value : __('frontWords.site_title'), 
                        'description' => $checkAudio->audio_title, 
                        'image' => (isset($logo->value) ? asset('public/images/sites/'.$logo->value) : asset('public/images/sites/mini_logo.webp')), 
                        "color" => "#3399cc", 
                        "email" => (isset(Auth::user()->email) ? Auth::user()->email : ''), 
                        "contact" => (Auth::user() != '' ? Auth::user()->mobile : '')
                    ];                    
                    $resp = ['status' => 1, 'data' => $data];
                }else{
                    $resp = ['status' => 0, 'msg' => __('frontWords.something_wrong')];
                }
            }else{
                $resp = $checkValidate;
            }
        }else{
            $resp = ['status' => 0, 'msg' => __('frontWords.login_err')];
        }
        echo json_encode($resp);
    }


    public function singleAudioPayment(Request $request){

        if(isset(Auth::user()->id)){
            $input = $request->all();
            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
            $payment = $api->payment->fetch($input['razorpay_payment_id']);
            if(count($input)  && !empty($input['razorpay_payment_id'])) {
                try{
                    $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount'=>$payment['amount'])); 
                }catch(\Exception $e){
                    echo json_encode(['status' => 0, 'msg' => $e->getMessage()]);
                }                
                
                $defaultSymbol = getDefaultCurrency();
                $audioDetail = Audio::where('id',$input['audio_id'])->get()->toArray();
                $audioData = (object)$audioDetail[0];
                
                $respObj = (object)['transaction_id' => $response['id'], 'payment_id' => $input['razorpay_payment_id'], 'amount' => $audioDetail[0]['download_price'], 'payment_gateway' => 'razorpay', 'order_id' => uniqid(), 'currency' => $defaultSymbol, 'user_name' => auth()->user()->name, 'user_email' => auth()->user()->email ];                
                
                $getResp = $this->saveAudioPaymentData([ 'user_id' => Auth::user()->id, 'artist_id' => $audioDetail[0]['user_id'], 'audio_id' => $input['audio_id'], 'audio_data' => $audioData, 'respObj' => $respObj, 'payment_gateway' => 'razorpay' ,'amount' => $audioDetail[0]['download_price'],'currency' => $defaultSymbol,'status' => '1']);                
                if(isset($request->is_ajax)){
                    echo json_encode(['status' => 1, 'msg' => __('frontWords.txn_id').' : '.$response['id'].'<br>'.__('frontWords.payment_done')]);
                }else{
                    alert()->success( __('frontWords.txn_id').' : '.$response['id'], __('frontWords.payment_done'))->persistent("Close");    
                    return redirect('/');
                }
            }else{
                echo json_encode(['status' => 0, 'msg' => __('frontWords.something_wrong')]);
            }
        }else{
            alert()->error( __('frontWords.login_err'))->persistent("Close");  
            return Redirect::back();
        }
    }

    function saveAudioPaymentData($param){        

            $paymentObj[] = $param['respObj'];
            $audioData[] = $param['audio_data'];
            $adminSettings = Settings::pluck('value','name');
            $sendData =  [
                'user_id' => $param['user_id'],
                'artist_id' => $param['artist_id'],
                'audio_id' => $param['audio_id'], 
                'payment_gateway' => $param['payment_gateway'],
                'amount' => $param['amount'],
                'currency' => $param['currency'],
                'status' => $param['status'],
                'audio_data' => json_encode($audioData),
                'payment_data' => json_encode($paymentObj),
                'order_id' => $param['respObj']->order_id
            ];         
            if(isset($adminSettings['is_commission']) && !empty($adminSettings['is_commission']) && $adminSettings['is_commission'] == 1){
                 $sendData['is_commission'] = $adminSettings['is_commission'];
                 $sendData['commission_type'] = $adminSettings['commission_type'];
                 $sendData['commission'] = $adminSettings['commission_val'];
            }
            
            $addUpdate = AdminAudioPayment::create($sendData);
            
            if($param['status'] == '1'){

                if($addUpdate){
                    $getAudioList = User::find(Auth::user()->id);
                    
                    if(isset($getAudioList->audio_download_list) && !empty($getAudioList->audio_download_list)){
                                            
                        $userIds = json_decode($getAudioList->audio_download_list);
                        if(!in_array($param['audio_id'], $userIds)){                   
                            array_push($userIds,$param['audio_id']);
                            $addupdatesong = User::where('id',$param['user_id'])->update(['audio_download_list'=>json_encode($userIds)]);
                        }

                    }else{                  
                        $addupdatesong = User::where('id',$param['user_id'])->update(['audio_download_list' => json_encode([$param['audio_id']])]);                   
                    }

                    $userAction = UserAction::updateOrCreate(['user_id' => Auth::user()->id, 'audio_id' => $param['audio_id']], ['download_count'=> DB::raw('download_count+1')] );                
                }                 

                if(!empty(env('MAIL_PASSWORD'))){
                    $this->paymentNotify(['amount' => $param['respObj']->currency.$param['respObj']->amount, 'txn_id' => $param['respObj']->transaction_id]);
                }
                return 1;
            }        
                return 0;
    }

    public function paymentNotify($param){

        $checkSmtpSetting = Settings::where('name', 'is_smtp')->first();
        if(!empty($checkSmtpSetting) && $checkSmtpSetting->value == 1){
            if(!empty(env('MAIL_PASSWORD'))){
                $users = User::find(Auth::user()->id);
                \Notification::send($users, new PaymentNotify(json_encode($param)));                
            }
        }else{
            return true;
        }

    }
}

