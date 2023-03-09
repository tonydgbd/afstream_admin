<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Modules\Coupon\Entities\Coupon;
use Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    function getUserCouponCode(){
        
        $userCoupon = [];
        $userCoupon = Coupon::where(['applicable_on'=>'0', 'status'=> '1',['expiry_date', '>=', date('Y-m-d')]])->get()->toArray();            
        if(isset(Auth::user()->id)){
            $planId = [];
            if(Auth::user()->plan_id == '0'){
                $planId[] = Auth::user()->plan_id;
            }else{
                $planId['0'] = '0';
                $planId['1'] = Auth::user()->plan_id;                    
            }
            
            $freeAndPlanCoupon = [];
            $userPlanCoupon = [];
            $userAllCoupon = [];
            
            $planId = Auth::user()->plan_id;                    
            $userCoupons = Coupon::where(['status'=> '1',['expiry_date', '>=', date('Y-m-d')]])->get()->toArray(); 
            if(!empty($userCoupons)){
                
                foreach($userCoupons as $coupon){
                    if($coupon != '' && !empty($coupon['plan_id'])){
                        $dataId = json_decode($coupon['plan_id']);
                        if(in_array($planId , $dataId)) {
                            $userPlanCoupon[] = $coupon;             
                        }
                    }
                }
                $userAllCoupon = Coupon::where(['applicable_on'=>'0', 'status'=> '1',['expiry_date', '>=', date('Y-m-d')]])->get()->toArray();
                $userCoupon = array_merge($userPlanCoupon,$userAllCoupon);
            }
        }
	   return $userCoupon;
	}
	
}
