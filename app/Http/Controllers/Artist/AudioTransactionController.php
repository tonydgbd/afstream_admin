<?php

namespace App\Http\Controllers\Artist;

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
use Modules\Setting\Entities\Currency;
use Stevebauman\Purify\Facades\Purify;
use App\ArtistPaymentRequest;
use App\ArtistPaymentGateway;
use Str;
use Auth;
use Crypt;
use Illuminate\Support\Facades\Storage;
use App\User;
use DB;

class AudioTransactionController extends Controller
{

    public function salesHistory(){
             
        return view('artist.transaction.sales_history');
    }

    public function salesHistoryData(Request $request){
       

        if(isset($request->from_date) && !empty($request->from_date) &&  $request->from_date != "Invalid date" && isset($request->to_date) && !empty($request->to_date)){
            $paymentData = select(['column' => ['users.name','audio.audio_title', 'admin_audio_payment.*'], 'table' => 'admin_audio_payment', 'order' => ['id','desc'], 'join' => [ ['users', 'users.id', '=', 'admin_audio_payment.user_id'],['audio', 'audio.id', '=', 'admin_audio_payment.audio_id'] ] ])->whereBetween('created_at', [$request->from_date, $request->to_date])->where('artist_id',Auth::user()->id);           
        }else{
            $paymentData = select(['column' => ['users.name','audio.audio_title', 'admin_audio_payment.*'], 'table' => 'admin_audio_payment', 'order' => ['id','desc'], 'join' => [ ['users', 'users.id', '=', 'admin_audio_payment.user_id'],['audio', 'audio.id', '=', 'admin_audio_payment.audio_id'] ] ])->where('artist_id',Auth::user()->id);
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
        
        ->editColumn('amount', function($paymentData){
            $getData = json_decode($paymentData->payment_data)[0];
            return $getData->currency.$getData->amount;
        })
        
        ->editColumn('payment_method', function($paymentData){
            $getData = json_decode($paymentData->payment_data)[0];
            $explode = explode('_',$getData->payment_gateway);
            return ucfirst($explode[0].(isset($explode[1]) ? ' '.$explode[1] : ''));
        })
        
        ->editColumn('commission', function($paymentData){
            $getData = json_decode($paymentData->payment_data)[0];
            if($paymentData->is_commission == '1'){
                $artistAmount = 0;
                if($paymentData->commission_type == 'percent'){
                    $artistAmount = ($paymentData->commission)*$getData->amount/100;
                }elseif($paymentData->commission_type == 'flat'){
                    $artistAmount = $getData->amount-$paymentData->commission;
                    $artistAmount = $getData->amount - $artistAmount;
                }
                return $getData->currency.$artistAmount;
            }else{
                return '';
            }
        })
        
        ->editColumn('tax_rate', function($paymentData){
            
            $is_tax = Settings::where('name', 'set_tax')->first();
            $tax = Settings::where('name', 'tax')->first();
            
            if(isset($is_tax) && !empty($is_tax) && $is_tax->value == '1' && !empty($tax->value)){
                return $tax->value.'%';
            }else{
                return '';
            }
        })
        
        ->editColumn('total_tax', function($paymentData){
            $is_tax = Settings::where('name', 'set_tax')->first();
            $tax = Settings::where('name', 'tax')->first();
            $getData = json_decode($paymentData->payment_data)[0];
            if(isset($is_tax) && !empty($is_tax) && !empty($tax)){
                
                return $getData->currency.number_format(($tax->value)*$getData->amount/100,2); 
            }else{
                return $getData->currency.'0';
            }
        })
        
        ->editColumn('earning', function($paymentData){
            $finalArtistAmount = 0;
            $is_tax = Settings::where('name', 'set_tax')->first();
            $tax = Settings::where('name', 'tax')->first();
            $getData = json_decode($paymentData->payment_data)[0];
            if($paymentData->is_commission == '1'){
                if($paymentData->commission_type == 'percent'){
                    $finalArtistAmount = ($paymentData->commission)*$getData->amount/100;
                }elseif($paymentData->commission_type == 'flat'){
                    $finalArtistAmount = $getData->amount-$paymentData->commission;
                    $finalArtistAmount = $getData->amount - $finalArtistAmount;
                }
            }
            if(isset($is_tax) && !empty($is_tax) && !empty($tax)){
                $finalArtistAmount += ($tax->value)*$getData->amount/100;
            }
            
            return $getData->currency. number_format($getData->amount-$finalArtistAmount,2);
        })
        
        ->editColumn('ordered_at', function($paymentData){
            return date('d-m-Y', strtotime($paymentData->created_at));
        })
        ->editColumn('status', function($paymentData){
            if($paymentData->status == 1){
                $stts = '<label class="mb-0 badge badge-success toltiped" data-original-title="" title="Success">Success</label>';
            }else if($paymentData->status == 2){
                $stts = '<label class="mb-0 badge badge-warning toltiped" data-original-title="" title="Pending">Pending</label>';
            }else if($paymentData->status == 0){
                $stts = '<label class="mb-0 badge badge-danger toltiped" data-original-title="" title="Cancelled">Fail</label>';
            }
            return $stts;
        })
        ->rawColumns(['order_id', 'status'])->make(true);
    }

    public function paymentHistory(){              
        $data = [];
        $data['payment_requests'] = ArtistPaymentRequest::where(['artist_id' => Auth::user()->id,'admin_status'=>'0'])->orderBy('created_at','desc')->get()->toArray(); 
        return view('artist.transaction.payment_history',$data);
    }

    public function paymentHistoryData(Request $request){
       
        if(isset($request->from_date) && !empty($request->from_date) &&  $request->from_date != "Invalid date" && isset($request->to_date) && !empty($request->to_date)){
            $artistPaymentData = select(['column' => ['artist_audio_payment.*'], 'table' => 'artist_audio_payment', 'order' => ['id','desc']])->whereBetween('created_at', [$request->from_date, $request->to_date])->where('artist_id',Auth::user()->id);                       
        }else{
            $artistPaymentData = select(['column' => ['artist_audio_payment.*'], 'table' => 'artist_audio_payment', 'order' => ['id','desc']])->where('artist_id',Auth::user()->id);               
        }

        return DataTables::of($artistPaymentData)
        ->addIndexColumn() 
        ->editColumn('order_id', function($artistPaymentData){
            return '<a href="'.url('artist/payment/'.$artistPaymentData->order_id).'" target="_blank">'.$artistPaymentData->order_id.'</a>';
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

    public function paymentRequest (){

        $data = [];
        $data['salesAmount'] = select(['column' => ['*'],'table' => 'admin_audio_payment'])->where('artist_id',Auth::user()->id)->sum('amount');        
        $data['salesAmountDetails'] = select(['column' => ['users.name','audio.audio_title', 'admin_audio_payment.*'], 'table' => 'admin_audio_payment', 'order' => ['id','desc'], 'join' => [ ['users', 'users.id', '=', 'admin_audio_payment.user_id'],['audio', 'audio.id', '=', 'admin_audio_payment.audio_id'] ] ])->where('artist_id' , Auth::user()->id)->where('status' , '1');

        $data['artistPaymentAmount'] = select(['column' => ['*'], 'table' => 'artist_audio_payment'])->where('artist_id',Auth::user()->id)->sum('amount');
        $data['artistPaymentAmountDetails'] = select(['column' => ['artist_audio_payment.*'], 'table' => 'artist_audio_payment', 'order' => ['id','desc'] ])->where('artist_id',Auth::user()->id)->where('status','1');

        $data['payment_requests'] = ArtistPaymentRequest::where('artist_id' , Auth::user()->id)->orderBy('created_at','desc')->get()->toArray();   
        $data['default_payment_gateway'] = ArtistPaymentGateway::select('default_pay_gateway')->where('user_id' , Auth::user()->id)->first();           
        return view('artist.transaction.payment_request',$data);
    }

    public function request_to_payment(Request $request){

        if($request->ajax()){

            $input = [];
            $input['request_amount'] = $request->request_amount;
            $input['artist_id'] = Auth::user()->id;
            $paymentRequest = ArtistPaymentRequest::create($input); 
            if($paymentRequest){
                $resp = array('status'=>1, 'msg'=> __('adminWords.detail').' '.__('adminWords.success_msg'));
            }else{
                $resp = array('status'=>0, 'msg'=> __('adminWords.error_msg'));
            }
            echo json_encode($resp);                      
        }
    }

    
}
