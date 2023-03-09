<?php

namespace Modules\Subscription\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use DataTables;

class SubscriptionController extends Controller{

    public function index(){
        return view('subscription::index');
    }

    public function subscriptionData(Request $request){
       
        if(isset($request->from_date) && !empty($request->from_date) &&  $request->from_date != "Invalid date" && isset($request->to_date) && !empty($request->to_date)){
            $paymentData = select(['column' => ['users.name', 'payment_gateways.*'], 'table' => 'payment_gateways', 'order' => ['id','desc'], 'join' => [ ['users', 'users.id', '=', 'payment_gateways.user_id'] ] ])->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }else{
            $paymentData = select(['column' => ['users.name', 'payment_gateways.*'], 'table' => 'payment_gateways', 'order' => ['id','desc'], 'join' => [ ['users', 'users.id', '=', 'payment_gateways.user_id'] ] ])->where('created_at', '>', now()->subDays(30)->endOfDay());  
        }
       
        return DataTables::of($paymentData)
        ->addIndexColumn() 
        ->editColumn('order_id', function($paymentData){
            if($paymentData->status == 1){
                $getSuccessId = select(['column' => 'id', 'table' => 'success_payments', 'where' => ['order_id' => $paymentData->order_id] ]);
                if(sizeof($getSuccessId) > 0){
                    $successId = $getSuccessId[0]->id;
                }else{
                    return redirect('admin');
                }
            }else{
                $successId = $paymentData->id;
            }
            return '<a href="'.url('user/invoice/'.$successId.'/'.$paymentData->order_id.'/'.($paymentData->status == 1 ? '1' : '0')).'" target="_blank">'.$paymentData->order_id.'</a>';
        })
        ->editColumn('qty', function($paymentData){
            return 1;
        })
        ->editColumn('name', function($paymentData){
            return ucfirst($paymentData->name);
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

}
