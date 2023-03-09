<?php

namespace App\Http\Controllers\api\v1;
 
use Modules\Setting\Entities\Notification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Notifications\PaymentNotify;
use Modules\Setting\Entities\Settings;
use Modules\Plan\Entities\Plan;
use Illuminate\Http\Request;
use App\UserPurchasedPlan;
use App\paymentGateway;
use App\SuccessPayment;
use App\AppVersion;
use App\User; 
use stdClass;
use DB;

class TransactionController extends Controller
{
    
    public $successStatus = true;
    public $errorStatus = false;
    public $errorMsg = 'Something went wrong.';


    function savePaymentData(Request $request){

        if($request->isMethod('post')){
            $user = Auth::user();
            $response['status'] = $this->errorStatus;          
            $response['msg'] = $this->errorMsg;


            if(!empty($user)){
                
                $userId = auth()->user()->id;
                $sendData =  [
                    'user_id' => $userId,
                    'type' => $_POST['type'],
                    'status' => 1,
                    'plan_id' => $_POST['plan_id'],
                    'payment_data' => $_POST['payment_data'],
                    'order_id' => $_POST['order_id']
                ];

                $paymentDecode = json_decode($_POST['payment_data']);    
                $addUpdate = SuccessPayment::create($sendData);

                if(!isset($_POST['manual_pay'])){
                    $addPayment = paymentGateway::create($sendData);
                }else{
                    $addPayment = 1;
                }
                
                $purchased_plan_date = date("Y-m-d", strtotime(date('Y-m-d')));
                $updatePlan = User::where('id', $userId)->update(['plan_id' => $_POST['plan_id'],'purchased_plan_date' => $purchased_plan_date]);
                $getPlan = Plan::find($_POST['plan_id']);

               
                $checkSmtpSetting = Settings::where('name', 'is_smtp')->first();
                if(!empty($checkSmtpSetting) && $checkSmtpSetting->value == 1){
                    if(!empty(env('MAIL_PASSWORD'))){
                        $this->paymentNotify(['amount' => $paymentDecode[0]->currency.$paymentDecode[0]->amount, 'txn_id' => $paymentDecode[0]->transaction_id]);
                    }
                }

                if(!empty($getPlan)){
                    $isDayMonth = $getPlan->is_month_days;
                    $daysMon = ($isDayMonth == 0) ? 'day' : 'month';
                    $planValid = $getPlan->validity;
                    $expiry_date = date("Y-m-d", strtotime("+".$planValid.' '.$daysMon, strtotime(date('Y-m-d'))));

                    $addPlanDetail = UserPurchasedPlan::create([
                        'user_id' => $userId,
                        'plan_id' => $_POST['plan_id'],
                        'order_id' => $_POST['order_id'], 
                        'plan_data' => json_encode($getPlan),
                        'payment_data' => $_POST['payment_data'], 
                        'currency' => $paymentDecode[0]->currency,
                        'expiry_date' => $expiry_date
                    ]); 
                } 

                $response['status'] = $this->successStatus;          
                $response['msg'] = 'Payment detail successfully saved';

            }else{            
                $response['msg'] = 'Unauthenticated.';            
            }        
            return response()->json($response);
            


        }

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
