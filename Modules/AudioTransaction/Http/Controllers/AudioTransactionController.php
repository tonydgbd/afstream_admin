<?php

namespace Modules\AudioTransaction\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\AdminAudioPayment;
use App\ArtistAudioPayment;
use Illuminate\Http\Response;
use DataTables;
use Illuminate\Support\Carbon;
use Modules\Audio\Entities\AudioGenre;
use Modules\Artist\Entities\ArtistGenre;
use Modules\Setting\Entities\Settings;
use Modules\Audio\Entities\AudioArtist;
use Modules\Audio\Entities\Audio;
use Modules\Artist\Entities\Artist;
use Modules\Language\Entities\Language;
use Modules\AudioLanguage\Entities\AudioLanguage;
use Stevebauman\Purify\Facades\Purify;
use Modules\Setting\Entities\Currency;
use App\ArtistPaymentRequest;
use App\ArtistPaymentGateway;
use Illuminate\Support\Facades\Storage;
use App\Notifications\PaymentNotify;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\PaymentExecution;
use PayPal\Api\ItemList; 
use PayPal\Api\Payer;
use PayPal\Api\Item;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Rest\ApiContext;
use PayPal\Api\ExecutePayment;
use Paystack;
use Stripe;
use PayPal\Api\Payment;
use App\paymentGateway; 
use Razorpay\Api\Api;
use App\User;
use Session;
use Redirect;
use Crypt;
use Str;
use Auth;
use DB;
use URL;

class AudioTransactionController extends Controller
{
    protected $currency_code = '';

    public function __construct()
    {   
        $dc = Settings::where('name', 'default_currency_id')->first();          
            if(!empty($dc)){
                $defaultCurrency = Currency::where('id',$dc->value)->first();
                if(!empty($defaultCurrency)){
                    $this->currency_code = $defaultCurrency->code;
                }else{
                    $this->currency_code = 'USD';
                }
            }
    }

    public function salesHistory(){
        
        $artists = DB::table('admin_audio_payment')->select('users.id', 'users.name','admin_audio_payment.artist_id')
            ->leftJoin('users', 'users.id', '=', 'admin_audio_payment.artist_id')->distinct()->get();       
        return view('audiotransaction::sales_history',['artists'=>$artists]);
    }

    public function salesHistoryData(Request $request){       

        if(isset($request->from_date) && !empty($request->from_date) &&  $request->from_date != "Invalid date" && isset($request->to_date) && !empty($request->to_date)){

            if(isset($request->artist_id) && !empty($request->artist_id)){
                $paymentData = select(['column' => ['users.name','audio.audio_title', 'admin_audio_payment.*'], 'table' => 'admin_audio_payment', 'order' => ['id','desc'], 'join' => [ ['users', 'users.id', '=', 'admin_audio_payment.user_id'],['audio', 'audio.id', '=', 'admin_audio_payment.audio_id'] ] ])->whereBetween('created_at', [$request->from_date, $request->to_date])->where('artist_id',$request->artist_id);
            }else{
                $paymentData = select(['column' => ['users.name','audio.audio_title', 'admin_audio_payment.*'], 'table' => 'admin_audio_payment', 'order' => ['id','desc'], 'join' => [ ['users', 'users.id', '=', 'admin_audio_payment.user_id'],['audio', 'audio.id', '=', 'admin_audio_payment.audio_id'] ] ])->whereBetween('created_at', [$request->from_date, $request->to_date]);
            }

        }else{
            $paymentData = select(['column' => ['users.name','audio.audio_title', 'admin_audio_payment.*'], 'table' => 'admin_audio_payment', 'order' => ['id','desc'], 'join' => [ ['users', 'users.id', '=', 'admin_audio_payment.user_id'],['audio', 'audio.id', '=', 'admin_audio_payment.audio_id'] ] ]);  
        }
        

        return DataTables::of($paymentData)
        ->addIndexColumn() 
        ->editColumn('order_id', function($paymentData){
            return '<a href="'.url('purchase/audio/'.$paymentData->order_id).'" target="_blank">'.$paymentData->order_id.'</a>';
        })
        ->editColumn('user_name', function($paymentData){
            return ucfirst($paymentData->name);
        })
        ->editColumn('audio_name', function($paymentData){
            return ucfirst($paymentData->audio_title);
        })

        ->editColumn('artist_name', function($paymentData){
            if(!empty($paymentData->artist_id)){
                $artistName = User::find($paymentData->artist_id);
                if(!empty($artistName)){
                    return ucfirst($artistName->name);
                }else{
                    return "";
                }
            }else{
                return "";
            }
        })
        ->editColumn('qty', function($paymentData){
            return 1; 
        })        
        ->editColumn('payment_method', function($paymentData){
            $getData = json_decode($paymentData->payment_data)[0];
            $explode = explode('_',$getData->payment_gateway);
            return ucfirst($explode[0].(isset($explode[1]) ? ' '.$explode[1] : ''));
        })
        ->editColumn('amount', function($paymentData){
            $getData = json_decode($paymentData->payment_data)[0];
            return $getData->currency.$getData->amount;
        })
        ->editColumn('ordered_at', function($paymentData){
            return date('d-m-Y', strtotime($paymentData->created_at));
        })
        ->editColumn('status', function($paymentData){
            if($paymentData->status == 1){
                $stts = '<label class="mb-0 badge badge-success toltiped" data-original-title="" title="Approved">Approved</label>';
            }else if($paymentData->status == 2){
                $stts = '<label class="mb-0 badge badge-warning toltiped" data-original-title="" title="Pending">Pending</label>';
            }else if($paymentData->status == 0){
                $stts = '<label class="mb-0 badge badge-danger toltiped" data-original-title="" title="Cancelled">Cancelled</label>';
            }
            return $stts;
        })
        ->rawColumns(['order_id', 'status'])->make(true);
    }

    public function paymentHistory(){        
        
        $artists = DB::table('admin_audio_payment')->select('users.id', 'users.name','admin_audio_payment.artist_id')
            ->leftJoin('users', 'users.id', '=', 'admin_audio_payment.artist_id')->distinct()->get();       
        return view('audiotransaction::payment_history',['artists'=>$artists]);
    }

    public function paymentHistoryData(Request $request){
       
        if(isset($request->from_date) && !empty($request->from_date) &&  $request->from_date != "Invalid date" && isset($request->to_date) && !empty($request->to_date)){

            if(isset($request->artist_id) && !empty($request->artist_id)){
                $artistPaymentData = select(['column' => ['users.name','artist_audio_payment.*'], 'table' => 'artist_audio_payment', 'order' => ['id','desc'], 'join' => [ ['users', 'users.id', '=', 'artist_audio_payment.artist_id']]])->whereBetween('created_at', [$request->from_date, $request->to_date])->where('artist_id',$request->artist_id);
            }else{
                $artistPaymentData = select(['column' => ['users.name','artist_audio_payment.*'], 'table' => 'artist_audio_payment', 'order' => ['id','desc'], 'join' => [ ['users', 'users.id', '=', 'artist_audio_payment.artist_id']]])->whereBetween('created_at', [$request->from_date, $request->to_date]);
            }
        }else{
            $artistPaymentData = select(['column' => ['users.name', 'artist_audio_payment.*'], 'table' => 'artist_audio_payment', 'order' => ['id','desc'], 'join' => [ ['users', 'users.id', '=', 'artist_audio_payment.artist_id'] ] ]);             
        }

        return DataTables::of($artistPaymentData)
        ->addIndexColumn() 
        ->editColumn('order_id', function($artistPaymentData){
            return '<a href="'.url('artist/payment/'.$artistPaymentData->order_id).'" target="_blank">'.$artistPaymentData->order_id.'</a>';
        })
        ->editColumn('artist_name', function($artistPaymentData){
            return ucfirst($artistPaymentData->name);
        })            
        ->editColumn('payment_method', function($artistPaymentData){
            $getData = json_decode($artistPaymentData->payment_data)[0];
            $explode = explode('_',$getData->payment_gateway);
            return ucfirst($explode[0].(isset($explode[1]) ? ' '.$explode[1] : ''));
        })
        ->editColumn('amount', function($artistPaymentData){
            $getData = json_decode($artistPaymentData->payment_data)[0];
            return $getData->currency.$getData->amount;
        })
        ->editColumn('ordered_at', function($artistPaymentData){
            return date('d-m-Y', strtotime($artistPaymentData->created_at));
        })
        ->editColumn('status', function($artistPaymentData){
            if($artistPaymentData->status == 1){
                $stts = '<label class="mb-0 badge badge-success toltiped" data-original-title="" title="Approved">Approved</label>';
            }else if($artistPaymentData->status == 2){
                $stts = '<label class="mb-0 badge badge-warning toltiped" data-original-title="" title="Pending">Pending</label>';
            }else if($artistPaymentData->status == 0){
                $stts = '<label class="mb-0 badge badge-danger toltiped" data-original-title="" title="Cancelled">Cancelled</label>';
            }
            return $stts;
        })
        ->rawColumns(['order_id', 'status'])->make(true);
    }

    public function paymentRequest(){                
        
        DB::table('artist_payment_request')->where(['admin_view' => '0'])->update(['admin_view' => '1']);
        $artists = DB::table('artist_payment_request')->select('users.id', 'users.name','artist_payment_request.artist_id')
            ->leftJoin('users', 'users.id', '=', 'artist_payment_request.artist_id')->distinct()->get();   

        return view('audiotransaction::payment_request',['artists'=>$artists]);
    }
 
    public function paymentRequestData(Request $request){

        if(isset($request->from_date) && !empty($request->from_date) &&  $request->from_date != "Invalid date" && isset($request->to_date) && !empty($request->to_date)){
            
            if(isset($request->artist_id) && !empty($request->artist_id)){
                $artistPaymentRequest = select(['column' => ['users.name', 'artist_payment_request.*'], 'table' => 'artist_payment_request', 'order' => ['id','desc'], 'join' => [ ['users', 'users.id', '=', 'artist_payment_request.artist_id'] ] ])->whereBetween('created_at', [$request->from_date, $request->to_date])->where('artist_id',$request->artist_id);
            }else{
                $artistPaymentRequest = select(['column' => ['users.name', 'artist_payment_request.*'], 'table' => 'artist_payment_request', 'order' => ['id','desc'], 'join' => [ ['users', 'users.id', '=', 'artist_payment_request.artist_id'] ] ])->whereBetween('created_at', [$request->from_date, $request->to_date]);
            }            
        }else{
            $artistPaymentRequest = select(['column' => ['users.name', 'artist_payment_request.*'], 'table' => 'artist_payment_request', 'order' => ['id','desc'], 'join' => [ ['users', 'users.id', '=', 'artist_payment_request.artist_id'] ] ]);  
        }

        return DataTables::of($artistPaymentRequest)
        ->addIndexColumn()               
       
        ->editColumn('artist_name', function($artistPaymentData){
            return ucfirst($artistPaymentData->name);
        })
        
      
        ->editColumn('amount', function($artistPaymentRequest){
            $dc = Settings::where('name', 'default_currency_id')->first();
            $defaultCurrency = '';
            if(!empty($dc)){
                $defaultCurrency = Currency::where('id',$dc->value)->first();
                if(!empty($defaultCurrency)){
                    $currency = $defaultCurrency->symbol;
                }else{
                    $currency = '$';
                }
            }
            return $currency.$artistPaymentRequest->request_amount;
        })
       
        ->editColumn('status', function($artistPaymentRequest){
            if($artistPaymentRequest->admin_status == 1){
                $adminStatus = '<label class="mb-0 badge badge-success toltiped" data-original-title="" title="Transferred">Transferred</label>';
            }else if($artistPaymentRequest->admin_status == 2){
                $adminStatus = '<label class="mb-0 badge badge-danger toltiped" data-original-title="" title="Rejected">Rejected</label>';
            }else if($artistPaymentRequest->admin_status == 0){
                $adminStatus = '<label class="mb-0 badge badge-warning toltiped" data-original-title="" title="Requested">Requested</label>';
            }
            return $adminStatus;
        })
        ->editColumn('bank_status', function($artistPaymentRequest){
            if($artistPaymentRequest->admin_status == 0 || $artistPaymentRequest->admin_status == 2){
                $bankStatus = '';
            }elseif($artistPaymentRequest->bank_status == 1){
                $bankStatus = '<label class="mb-0 badge badge-success toltiped" data-original-title="" title="Success">Success</label>';
            }else if($artistPaymentRequest->bank_status == 2){
                $bankStatus = '<label class="mb-0 badge badge-warning toltiped" data-original-title="" title="Pending">Pending</label>';
            }else if($artistPaymentRequest->bank_status == 0){
                $bankStatus = '<label class="mb-0 badge badge-danger toltiped" data-original-title="" title="Fail">Fail</label>';
            }
            return $bankStatus;
        })


        ->editColumn('request_date', function($artistPaymentRequest){
            return date('d-m-Y', strtotime($artistPaymentRequest->created_at));
        })
 
        ->editColumn('action', function($artistPaymentRequest){
            if($artistPaymentRequest->admin_status == 0 || $artistPaymentRequest->admin_status == 2){

                $requested = '';
                $reject = '';

                if($artistPaymentRequest->admin_status == "0"){
                    $requested = 'selected';
                }elseif($artistPaymentRequest->admin_status == "2"){
                    $reject = 'selected';
                }

                $checkDefaultGateway = ArtistPaymentGateway::where('user_id',$artistPaymentRequest->artist_id)->first();
                if($checkDefaultGateway->default_pay_gateway == 'paypal'){
                    $paymentGateway = 'paypal';
                }else{
                    $paymentGateway = '';
                }
                return '<div> 
                    <select class="form-control btn-square artistPaymentReleaseByAdmin" style="width: 58%;" data-url="'.url('/admin/artistReleasePayment/'.$artistPaymentRequest->id).'" payment-gateway="'.$paymentGateway.'">                  
                        <option value="">'.__('adminWords.change_status').'</option>         
                        <option value="0" '.$requested.'>'.__('adminWords.requested').'</option>         
                        <option value="1" >'.__('adminWords.pay_now').'</option> 
                        <option value="2" '.$reject.'>'.__('adminWords.reject').'</option>
                    </select> 
                </div>';                
            }else{
                return '';
            }

        })
        
        ->rawColumns(['status','bank_status','action'])->make(true);
    }


    function artistReleasePayment(Request $request, $id){

        $response['status'] = false;    
        if($request->optionValue != '1'){
            
            change_status(['table'=>'artist_payment_request', 'column'=>'artist_id', 'where'=>['id'=>$id],'data'=> ['admin_status'=>$request->optionValue]]);
            $response['status'] = true;
            $response['msg'] = __('adminWords.status').' '.__('adminWords.updated_msg');

        }else{
            $defaultCurrency = Currency::where('active',1)->first();
            if(!empty($defaultCurrency)){
                $currency = $defaultCurrency->code;
            }else{
                $response['msg'] = __('adminWords.error_default_currency');
                return response()->json($response);  
            }
            
            $requestDetails = ArtistPaymentRequest::find($id);    
            
            if(!empty($requestDetails) && $requestDetails->request_amount > 0){

                $checkDefaultGateway = ArtistPaymentGateway::where('user_id',$requestDetails->artist_id)->first();   
                $usersDetail = User::find($requestDetails->artist_id);

                if(isset($checkDefaultGateway->default_pay_gateway) && !empty($checkDefaultGateway->default_pay_gateway) && isset($usersDetail) && !empty($usersDetail)){ 
                    if($checkDefaultGateway->default_pay_gateway == 'razorpay'){
                        $logo = Settings::where('name', 'mini_logo')->first();
                        
                        
                        $data = [
                            'request_id' => $id,
                            'razorpay_key' => $checkDefaultGateway->razorpay_key,
                            'amount' => ($requestDetails->request_amount)*100,
                            'pay_method' => $checkDefaultGateway->default_pay_gateway,
                            'currency' => isset($currency) ? $currency : 'USD',
                            'description' => 'Request Date '.date('d/m/Y H:i', strtotime($requestDetails->created_at)), 
                            'image' => (isset($logo->value) ? asset('public/images/sites/'.$logo->value) : asset('public/images/sites/mini_logo.webp')), 
                            "color" => "#3399cc", 
                            'name' => $usersDetail->name, 
                            "email" => $usersDetail->email, 
                            "contact" => $usersDetail->mobile
                        ];

                        $response['status'] = true;    
                        $response['data'] = $data;
                        return response()->json($response);                   

                    }elseif($checkDefaultGateway->default_pay_gateway == 'paypal'){
                        $data = [
                            'request_id' => $id,
                            'amount' => ($requestDetails->request_amount)*100,
                            'pay_method' => $checkDefaultGateway->default_pay_gateway,
                            'currency' => isset($currency) ? $currency : 'USD', 
                            'name' => $usersDetail->name, 
                            "email" => $usersDetail->email,
                            "url" => url('/admin/artistReleasePaymentByPaypal')
                        ];

                        $response['status'] = true;    
                        $response['data'] = $data;
                        return response()->json($response);     

                    }elseif($checkDefaultGateway->default_pay_gateway == 'stripe'){
                        $data = [
                            'request_id' => $id,
                            'amount' => ($requestDetails->request_amount)*100,
                            'pay_method' => $checkDefaultGateway->default_pay_gateway,
                            'currency' => isset($currency) ? $currency : 'USD', 
                            'name' => $usersDetail->name, 
                            "email" => $usersDetail->email,
                            "url" => url('/stripe/artistReleasePayment')
                        ];

                        $response['status'] = true;    
                        $response['data'] = $data;
                        return response()->json($response);

                    }elseif($checkDefaultGateway->default_pay_gateway == 'paystack'){

                        $title = Settings::where('name', 'w_title')->first();
                        $logo = Settings::where('name', 'mini_logo')->first();                        
                        $data = [
                            'request_id' => $id,
                            'reference' => Paystack::genTranxRef(), 
                            'paystack_key' => $checkDefaultGateway->paystack_public_key, //env('PAYSTACK_PUBLIC_KEY'),
                            'pay_method' => $checkDefaultGateway->default_pay_gateway,
                            'currency' => isset($currency) ? $currency : 'USD', 
                            'amount' => ($requestDetails->request_amount)*100,
                            'name' => isset($title->value) ? $title->value : __('frontWords.site_title'), 
                            'description' => 'Request Date '.date('d/m/Y H:i', strtotime($requestDetails->created_at)), 
                            'image' => (isset($logo->value) ? asset('public/images/sites/'.$logo->value) : asset('public/images/sites/mini_logo.webp')), 
                            "color" => "#3399cc", 
                            "user_name" => $usersDetail->name, 
                            "email" => $usersDetail->email,
                            "contact" => $usersDetail->mobile
                        ];   
                        $response['status'] = true; 
                        $response['data'] = $data;
                        return response()->json($response);
                    }
                }                    
            }else{
                $response['msg'] = __('frontWords.something_wrong');
            }
        }

        return response()->json($response);    
    }
    

    public function releasePaymentRazorpayCallback(Request $request){

        if(isset(Auth::user()->id)){

            $input = $request->all();
            $requestDetails = ArtistPaymentRequest::find($input['request_id']);
            $gatewayDetail = ArtistPaymentGateway::where('user_id',$requestDetails->artist_id)->first();
            $usersDetail = User::find($requestDetails->artist_id);

            $api = new Api($gatewayDetail->razorpay_key, $gatewayDetail->razorpay_secret);
            $payment = $api->payment->fetch($input['razorpay_payment_id']);


            if(count($input)  && !empty($input['razorpay_payment_id'])) {
                try{
                    $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount'=>$payment['amount'])); 
                }catch(\Exception $e){
                    echo json_encode(['status' => 0, 'msg' => $e->getMessage()]);
                }                              
                $respObj = (object)['transaction_id' => $response['id'], 'payment_id' => $input['razorpay_payment_id'], 'amount' => round($response['amount']/100, 2), 'payment_gateway' => 'razorpay', 'order_id' => uniqid(), 'currency' => getCurrency(['curr_code' => $response['currency'] ]), 'user_name' => 
                    $usersDetail->name, 'user_email' => $usersDetail->email ];                
                  
                $getResp = $this->saveArtistReleasePaymentData([ 'request_id' => $input['request_id'], 'artist_id' => $requestDetails->artist_id, 'payment_data' => $respObj, 'payment_gateway' => 'razorpay' ,'amount' => round($response['amount']/100, 2),'currency' => getCurrency(['curr_code' => $response['currency'] ]),'status' => '1']);                  

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


    public function artistReleasePaymentByPaypal(Request $request){
        
        if(isset(Auth::user()->id)){

            if(isset($request->request_id) && !empty($request->request_id)){
                $input = $request->all();
                $requestDetails = ArtistPaymentRequest::find($input['request_id']);
                $gatewayDetail = ArtistPaymentGateway::where('user_id',$requestDetails->artist_id)->first();
                $usersDetail = User::find($requestDetails->artist_id);
              
                $setcurrency = isset(session()->get('currency')['symbol']) ? getCurrency(['curr_code' => session()->get('currency')['symbol']]) : 'USD';             
                $payout = $requestDetails->request_amount;                
                $payer = new Payer();
                $payer->setPaymentMethod('paypal');
                $item_1 = new Item();

                $item_1->setName('Request Date '.date('d/m/Y H:i', strtotime($requestDetails->created_at)))
                    ->setCurrency($setcurrency)->setQuantity(1)
                    ->setPrice($payout);
                $item_list = new ItemList();
                $item_list->setItems(array(
                    $item_1,
                ));

                $amount = new Amount();
                $amount->setCurrency($setcurrency)->setTotal($payout);
                $transaction = new Transaction();
                $transaction->setAmount($amount)->setItemList($item_list)->setDescription('Payment for '.$usersDetail->name.' plan');
                $redirect_urls = new RedirectUrls();
                $redirect_urls->setReturnUrl(url('/paypal/artistReleasePaymentStatus'))
                    ->setCancelUrl(URL::to('/checkout/artistReleasePaymentPaypal'));
                $payment = new Payment();
                $payment->setIntent('Sale')
                    ->setPayer($payer)->setRedirectUrls($redirect_urls)->setTransactions(array(
                    $transaction,
                ));

                $paypal_conf = \Config::get('paypal');
                $api_context = new ApiContext(new OAuthTokenCredential($gatewayDetail->paypal_client_id, $gatewayDetail->paypal_secret));
                $api_context->setConfig($paypal_conf['settings']);

                try{
                    $payment->create($api_context);
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
                
                Session::put('paypal_artist_payment_id', $payment->getId());
                Session::put('paypal_artist_payment_request_id', $request->request_id);
                Session::put('paypal_artist_payment_artist_id', $requestDetails->artist_id);
                Session::put('artist_payment_amount_currency', $requestDetails->request_amount.'-'.$setcurrency);
                Session::put('artist_payment_name', $usersDetail->name);
                Session::put('artist_payment_email', $usersDetail->email);  

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


    public function artistReleasePaymentPaypalStatus(Request $request){

        $payment_id = Session::get('paypal_artist_payment_id');
        $request_id = Session::get('paypal_artist_payment_request_id'); 
        $artist_id = Session::get('paypal_artist_payment_artist_id'); 
        $artist_name = Session::get('artist_payment_name'); 
        $artist_email = Session::get('artist_payment_email'); 
        Session::forget('paypal_artist_payment_id');

        $req = $request->all();
        
        $gatewayDetail = ArtistPaymentGateway::where('user_id',$artist_id)->first();
        $paypal_conf = \Config::get('paypal');
        $api_context = new ApiContext(new OAuthTokenCredential($gatewayDetail->paypal_client_id, $gatewayDetail->paypal_secret));
        $api_context->setConfig($paypal_conf['settings']);

        if (empty($req['PayerID']) || empty($req['token'])) {
            alert()->error( __('frontWords.payment_fail'))->persistent("Close");  
            return Redirect::back();
        }
        
        $payment = Payment::get($payment_id, $api_context);
      
        $execution = new PaymentExecution();
        $execution->setPayerId($req['PayerID']);
        $result = $payment->execute($execution, $api_context);
        
        $respObj = (object)['transaction_id' => $result->id, 'payment_id' => $payment_id, 'amount' => $result->transactions[0]->amount->total, 'payment_gateway' => 'paypal', 'order_id' => uniqid(), 'currency' => getCurrency(['curr_code' => $result->transactions[0]->amount->currency]), 'user_name' => $artist_name, 'user_email' => $artist_email ];                
        

        if ($result->getState() == 'approved') {             
            /** Here Write your database logic like that insert record or value in database if you want **/
            $getResp = $this->saveArtistReleasePaymentData([ 'request_id' => $request_id, 'artist_id' => $artist_id, 'payment_data' => $respObj, 'payment_gateway' => 'paypal' ,'amount' => $result->transactions[0]->amount->total,'currency' => getCurrency(['curr_code' => $result->transactions[0]->amount->currency]) ,'status' => '1']);  

            alert()->success( __('frontWords.txn_id').' : '.$result->id, __('frontWords.payment_done'))->persistent("Close");    
            return redirect()->back();
        }else{
            $getResp = $this->saveArtistReleasePaymentData([ 'request_id' => $request_id, 'artist_id' => $artist_id, 'payment_data' => $respObj, 'payment_gateway' => 'paypal' ,'amount' => $result->transactions[0]->amount->total,'currency' => getCurrency(['curr_code' => $result->transactions[0]->amount->currency]) ,'status' => '0']);           
        }
        Session::forget(['plan_id', 'custom_id', 'discount_and_amount']);
        alert()->error( __('frontWords.payment_fail'))->persistent("Close");  
        return redirect()->back();
    }

    public function paypalCancelReturn(Request $request){

        $payment_id = Session::get('paypal_artist_payment_id');
        $request_id = Session::get('paypal_artist_payment_request_id'); 
        $artist_id = Session::get('paypal_artist_payment_request_id'); 
        $artist_name = Session::get('artist_payment_name'); 
        $artist_email = Session::get('artist_payment_email');
        $explodeAmntCurr = explode('-', Session::get('artist_payment_amount_currency'));

        $respObj = (object)['transaction_id' => $request->token, 'payment_id' => $payment_id, 'amount' => $explodeAmntCurr[0], 'payment_gateway' => 'paypal', 'order_id' => uniqid(), 'currency' => getCurrency(['curr_code' => $explodeAmntCurr[1]]), 'user_name' => $artist_name, 'user_email' => $artist_email]; 

        $getResp = $this->saveArtistReleasePaymentData([ 'request_id' => $request_id, 'artist_id' => $artist_id, 'payment_data' => $respObj, 'payment_gateway' => 'paypal' ,'amount' => $explodeAmntCurr[0],'currency' => getCurrency(['curr_code' => $explodeAmntCurr[1]]) ,'status' => '0']);  

        alert()->error( __('frontWords.payment_fail'))->persistent("Close");  
        return Redirect::back();
    }
    

    public function artistStripePayment(Request $request){
        if(isset(Auth::user()->id)){
            $checkValidate = validation($request->all(), [
                'number' => 'required',
                'name' => 'required',
                'expiry' => 'required',
                'cvc' => 'required|max:3',
               'request_id' => 'required',
            ]);
            
            if($checkValidate['status'] == 1){
                try{

                    $input = $request->except('_token');
                    $requestDetails = ArtistPaymentRequest::find($input['request_id']);
                    $gatewayDetail = ArtistPaymentGateway::where('user_id',$requestDetails->artist_id)->first();
                    $usersDetail = User::find($requestDetails->artist_id);

                    if(!empty($gatewayDetail)){

                        $stripe = Stripe::make($gatewayDetail->stripe_secret);
                        if($stripe == '' || $stripe == null){
                            return json_encode(['status'=>0, 'msg'=> __('frontWords.stripe_err'), 'swal' => 0]);
                        }
                        $monthYear = explode('/',$request->expiry);

                        if(isset($monthYear[1]) && !empty($monthYear[1])){

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
                        }else{
                            return json_encode(['status'=> 0, 'msg'=>__('adminWords.card_formate_error'), 'swal' => 0]);
                        }
                        
                        $charge = $stripe->charges()->create([
                            'card' => $token['id'],
                            'currency' => $this->currency_code,
                            'amount' => $requestDetails->request_amount,
                            'description' => 'Request Date - '.date('d/m/Y H:i', strtotime($requestDetails->created_at)), 
                        ]);                    

                        $respObj = (object)['transaction_id' =>$charge['balance_transaction'], 'amount' => $requestDetails->request_amount, 'payment_gateway' => 'stripe', 'order_id' => uniqid(), 'currency' => getCurrency(['curr_code' => $charge['currency']]), 'user_name' => $usersDetail->name, 'user_email' => $usersDetail->email]; 

                            if($charge['status'] == 'succeeded'){                                
                                $getResp = $this->saveArtistReleasePaymentData([ 'request_id' => $input['request_id'], 'artist_id' => $requestDetails->artist_id, 'payment_data' => $respObj, 'payment_gateway' => 'stripe' ,'amount' => $requestDetails->request_amount, 'currency' => getCurrency(['curr_code' => $charge['currency']]),'status' => '1']);     
                                if($getResp){                            
                                    $resp = ['status'=>1, 'msg'=> __('frontWords.txn_id').' : '.$charge['balance_transaction'].' '. __('frontWords.payment_done'), 'swal' => 1 ];
                                }else{
                                    $resp = ['status'=>0, 'msg'=> __('frontWords.try_again'), 'swal' => 1 ];
                                }

                            }else{
                                $getResp = $this->saveArtistReleasePaymentData([ 'request_id' => $input['request_id'], 'artist_id' => $requestDetails->artist_id, 'payment_data' => $respObj, 'payment_gateway' => 'stripe' ,'amount' => $requestDetails->request_amount, 'currency' => getCurrency(['curr_code' => $charge['currency']]),'status' => '0']);                                   
                                $resp = ['status'=>0, 'msg'=> __('frontWords.try_again'), 'swal' => 1 ];
                            }

                    }else{
                        $resp = ['status'=>0, 'msg'=> __('frontWords.try_again'), 'swal' => 1 ];
                    }

                }catch(\Exception $e){
                    $resp = ['status'=>0, 'msg'=> $e->getMessage(), 'swal' => 1 ];
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


    public function paystackArtistReleaseCallback(Request $request){

        if(isset(Auth::user()->id)){

            $input = $request->except('_token');
            $requestDetails = ArtistPaymentRequest::find($input['request_id']);
            
            if(!empty($input) && !empty($requestDetails)) {

                $gatewayDetail = ArtistPaymentGateway::where('user_id',$requestDetails->artist_id)->first();
                $usersDetail = User::find($requestDetails->artist_id);
                
                
                $respObj = (object)['transaction_id' => $input['transaction'], 'payment_id' => $input['reference'], 'amount' => round($requestDetails->request_amount/100, 2), 'payment_gateway' => 'paystack', 'order_id' => uniqid(), 'currency' => getCurrency(['curr_code' => $input['currency']]), 'user_name' => $usersDetail->name, 'user_email' => $usersDetail->email ];                
                
                if($input['status'] == 'success'){
                    $getResp = $this->saveArtistReleasePaymentData([ 'request_id' => $input['request_id'], 'artist_id' => $requestDetails->artist_id, 'payment_data' => $respObj, 'payment_gateway' => 'paystack' ,'amount' => round($requestDetails->request_amount/100, 2), 'currency' => getCurrency(['curr_code' => $input['currency']]),'status' => '1']);   
                        echo json_encode(['status' => 1, 'msg' => __('frontWords.txn_id').' : '.$input['transaction'].'<br>'.__('frontWords.payment_done')]);
                }else{
                    $getResp = $this->saveArtistReleasePaymentData([ 'request_id' => $input['request_id'], 'artist_id' => $requestDetails->artist_id, 'payment_data' => $respObj, 'payment_gateway' => 'paystack' ,'amount' => round($requestDetails->request_amount/100, 2), 'currency' => getCurrency(['curr_code' => $input['currency']]),'status' => '0']);
                        echo json_encode(['status' => 0, 'msg' => __('frontWords.txn_id').' : '.$input['transaction'].'<br>'.__('frontWords.payment_fail')]);
                }             

            }else{
                echo json_encode(['status' => 0, 'msg' => __('frontWords.something_wrong')]);
            }
        }else{
            echo json_encode(['status' => 0, 'msg' => __('frontWords.login_err')]);
        }
    }
    


    function saveArtistReleasePaymentData($param){     

        $paymentObj[] = $param['payment_data'];
        $sendData =  [
            'artist_id' => $param['artist_id'],
            'payment_data' => json_encode($paymentObj),
            'amount' => $param['amount'],
            'currency' => $param['currency'],
            'payment_gateway' => $param['payment_gateway'],
            'order_id' => $param['payment_data']->order_id,
            'status' => $param['status'],
        ];         

        $addUpdate = ArtistAudioPayment::create($sendData);
        if($addUpdate){
            if($param['status'] == '1'){
                $updateRequest = ArtistPaymentRequest::where('id',$param['request_id'])->update(['admin_status'=> '1','bank_status'=> '1','order_id'=>$param['payment_data']->order_id]);
            }else{
                $updateRequest = ArtistPaymentRequest::where('id',$param['request_id'])->update(['admin_status'=> '1','bank_status'=> '0','order_id'=>$param['payment_data']->order_id]);
            }
        }        
        if(!empty(env('MAIL_PASSWORD'))){
            $this->paymentNotify(['amount' => $param['payment_data']->currency.$param['payment_data']->amount,'artist_id' => $param['artist_id'], 'txn_id' => $param['payment_data']->transaction_id]);
        }
        return 1;
    }


    public function paymentNotify($param){

        $checkSmtpSetting = Settings::where('name', 'is_smtp')->first();
        if(!empty($checkSmtpSetting) && $checkSmtpSetting->value == 1){
            if(!empty(env('MAIL_PASSWORD'))){
                $users = User::find($param['artist_id']);
                \Notification::send($users, new PaymentNotify(json_encode($param)));                
            }
        }else{
            return true;
        }
    }




}
