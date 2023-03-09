<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Audio\Entities\Audio;
use Modules\Setting\Entities\Settings;
use App\AdminAudioPayment;
use App\User;
use App\UserAction;
use App\Notifications\PaymentNotify;
use Auth;
use DB;

class ArtistAudioController extends Controller
{

    public $successStatus = true;
    public $errorStatus = false;
    public $errorMsg = 'Something went wrong.';

    public function saveAudioPaymentData(Request $request){        

        if($request->isMethod('post')){

            $user = Auth::user();
            $response['status'] = $this->errorStatus;          
            $response['msg'] = $this->errorMsg;

            if(!empty($user)){
               
                if(isset($request->audio_id) && !empty($request->audio_id) && isset($request->payment_gateway) && !empty($request->payment_gateway) && isset($request->payment_data) && !empty($request->payment_data) && isset($request->status) && !empty($request->status)){
                    
                    $audioDetail = Audio::where('id',$request->audio_id)->get()->toArray();       
                    $audioData = (object)$audioDetail[0];
                    $paymentDecode = json_decode($_POST['payment_data']);  
                    $adminSettings = Settings::pluck('value','name');
                    $sendData =  [
                        'user_id' => Auth::user()->id,
                        'audio_id' => $request->audio_id, 
                        'artist_id' => $audioDetail[0]['user_id'],
                        'amount' => $audioDetail[0]['download_price'],
                        'payment_gateway' => $request->payment_gateway,
                        'audio_data' => json_encode($audioData),
                        'payment_data' => $request->payment_data,
                        'currency' => $paymentDecode[0]->currency,
                        'order_id' => $paymentDecode[0]->order_id,
                        'status' => $request->status,
                    ];         
                    if(isset($adminSettings['is_commission']) && !empty($adminSettings['is_commission']) && $adminSettings['is_commission'] == 1){
                         $sendData['is_commission'] = $adminSettings['is_commission'];
                         $sendData['commission_type'] = $adminSettings['commission_type'];
                         $sendData['commission'] = $adminSettings['commission_val'];
                    }
                    
                    $addUpdate = AdminAudioPayment::create($sendData);
                    
                    if($request->status == 1){
                            
                            // if($addUpdate){
                                
                            //     $getAudioList = User::find(Auth::user()->id);
                            //     if(isset($getAudioList->audio_download_list) && !empty($getAudioList->audio_download_list)){
                            //         $userIds = json_decode($getAudioList->audio_download_list);
                            //         if(!in_array($request->audio_id, $userIds)){                   
                            //             array_push($userIds,$request->audio_id);
                            //             $addupdatesong = User::where('id',Auth::user()->id)->update(['audio_download_list'=>json_encode($userIds)]);
                            //         }
                            //     }else{                  
                            //         $addupdatesong = User::where('id',Auth::user()->id)->update(['audio_download_list' => json_encode([$request->audio_id])]);
                            //     }
                            // }
                            
                            $userAction = UserAction::updateOrCreate(['user_id' => Auth::user()->id, 'audio_id' => $request->audio_id,'download' => 1], ['download_count'=> DB::raw('download_count+1')] );                

                        if(!empty(env('MAIL_PASSWORD'))){
                            $this->paymentNotify(['amount' => $paymentDecode[0]->currency.$audioDetail[0]['download_price'], 'txn_id' => $paymentDecode[0]->transaction_id]);
                        }

                        $response['status'] = $this->successStatus;          
                        $response['msg'] = 'Payment detail successfully saved.';

                    }else{

                        $response['status'] = $this->errorStatus;          
                        $response['msg'] = 'Payment failed';
                    }      
                }else{
                    $response['msg'] = 'All fields are required.';
                }

            }else{
                $response['msg'] = 'Unauthenticated.'; 
            }
            return response()->json($response);
        }
    }



    public function remove_to_download_artist_track(Request $request){

        if(isset(Auth::user()->id)){

            if(isset($request->audio_id) && !empty($request->audio_id)){     

                 $decodeIds = User::select('audio_download_list')->where(['id'=> Auth::user()->id])->first();
                    if($decodeIds != '' && !empty($decodeIds->audio_download_list && $decodeIds->audio_download_list != '[]')){
                        $dataId = json_decode($decodeIds->audio_download_list);
                        $key = array_search($request->audio_id, $dataId); 
                        unset($dataId[$key]);
                        $new_arr = array_values($dataId);
                        $update = User::where('id', Auth::user()->id)->update(['audio_download_list' => json_encode($new_arr)]);
                        $response['status'] = $this->successStatus;
                    }else{                        
                        $response['status'] = $this->errorStatus;
                    }

            }else{
                $response['status'] = $this->errorStatus;
                $response['msg'] = "Audio id is required.";
            }

        }else{
            $response['status'] = $this->errorStatus;
            $response['msg'] = 'Unauthenticated.';
        }
        return response()->json($response);  
    }


    public function paymentNotify($param){
        $response = [];
        $checkSmtpSetting = Settings::where('name', 'is_smtp')->first();
        if(!empty($checkSmtpSetting) && $checkSmtpSetting->value == 1){
            if(!empty(env('MAIL_PASSWORD'))){
                $users = User::find(Auth::user()->id);
                try {
                    \Notification::send($users, new PaymentNotify(json_encode($param)));                
                }catch (\Exception $e) {
                    $response['smtp_error'] = __('adminWords.smtp_setting_error').$e->getMessage();
                }
            }
        }else{
            return true;
        }

    }


}
