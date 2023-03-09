<?php
namespace Modules\Coupon\Http\Controllers;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Coupon\Entities\Coupon;
use DataTables;
use Illuminate\Support\Carbon;
use Modules\Plan\Entities\Plan;

class CouponController extends Controller
{
    public function index(){
        return view('coupon::index');
    } 

    public function couponData(){
        $coupon = select(['column' => ['*'], 'table' => 'coupons', 'order'=>['id','desc'] ]);
        return DataTables::of($coupon)
            ->editColumn('checkbox',function($coupon){
                return '<div class="checkbox danger-check"><input name="checked" id="checkboxAll'.$coupon->id.'" type="checkbox" class="CheckBoxes" value="'.$coupon->id.'"><label for="checkboxAll'.$coupon->id.'"></label></div>';
            })
            ->editColumn('description', function($coupon){
                return '<p class="limited-text-pera">'.$coupon->description.'<p>';
            })
            ->editColumn('starting_date', function($coupon){
                return date('d-m-Y', strtotime($coupon->starting_date));
            })
            ->editColumn('expiry_date', function($coupon){
                return date('d-m-Y', strtotime($coupon->expiry_date));
            })
            ->editColumn('applicable_on', function($coupon){
                return $coupon->applicable_on == 0 ? 'All Section' : 'Plans';
            })
            ->editColumn('created_at', function($coupon){
                return date('d-m-Y', strtotime($coupon->created_at));
            })
            ->editColumn('status', function($coupon){
                return '<div class="checkbox success-check"><input id="checkboxc'.$coupon->id.'" name="status" class="updateStatus" '.($coupon->status == 1 ? 'checked':'').' type="checkbox" data-url="'.url('coupon/status/'.$coupon->id).'"><label for="checkboxc'.$coupon->id.'"></label></div>';
            })
            ->addColumn('action', function ($coupon) {
                return '<a class="action-btn " href="javascript:void(0); ">
                    <svg class="default-size "  viewBox="0 0 341.333 341.333 ">
                        <g>
                            <g>
                                <g>
                                    <path d="M170.667,85.333c23.573,0,42.667-19.093,42.667-42.667C213.333,19.093,194.24,0,170.667,0S128,19.093,128,42.667 C128,66.24,147.093,85.333,170.667,85.333z "></path>
                                    <path d="M170.667,128C147.093,128,128,147.093,128,170.667s19.093,42.667,42.667,42.667s42.667-19.093,42.667-42.667 S194.24,128,170.667,128z "></path>
                                    <path d="M170.667,256C147.093,256,128,275.093,128,298.667c0,23.573,19.093,42.667,42.667,42.667s42.667-19.093,42.667-42.667 C213.333,275.093,194.24,256,170.667,256z "></path>
                                </g>
                            </g>
                        </g>
                    </svg>
                </a>
                <div class="action-option ">
                    <ul>
                        <li>
                            <a class="audioGenrePopupToggle" href="'.url('coupon/edit/'.$coupon->id).'"><i class="far fa-edit mr-2 "></i>'.__('adminWords.edit').'</a>
                        </li>
                        <li>
                            <a href="javascript:void(0); " data-url="'.url('coupon/destroy/'.$coupon->id).'" id="deleteRecordById"><i class="far fa-trash-alt mr-2 "></i>'.__('adminWords.delete').'</a>
                        </li>
                    </ul>
                </div>';
            })
            ->rawColumns(['checkbox','description','status','action'])->make(true);
    }

    public function addEditCoupon(Request $request, $id){
        $rules = [ 'coupon_code' => 'required','discount_type' => 'required', 'coupon_used_count' => 'required', 'starting_date' => 'required', 'expiry_date' => 'required' ];
        if($request->applicable_on == 1){
            $rules['plan_id'] = 'required';
        }
       
        $checkValidate = validation($request->except('_token'), $rules);
        if($checkValidate['status'] == 1){
            $where = is_numeric($id) ? [['id','!=',$id],['coupon_code','=',$request->coupon_code] ] : [ ['coupon_code','=',$request->coupon_code] ];
            $checkCoupon = Coupon::where($where)->first();
            if(!empty($checkCoupon) > 0){
                $resp = array('status'=>0, 'msg'=> __('adminWords.coupon').' '.__('already_exist'));
            }else{
                $checkCoupon = is_numeric($id) ? Coupon::find($id) : [];
                $data = $request->except('_token');
                $data['status'] = isset($request->status) ? 1 : 0;
                $data['plan_id'] = $request->applicable_on == 1 ? json_encode($request->plan_id) : '';
                
                $addCoupon = empty($checkCoupon) ? Coupon::create($data) : $checkCoupon->update($data);
                $resp = ($addCoupon) ? ['status'=>1, 'msg'=> __('adminWords.coupon').' '.__('adminWords.success_msg')] : ['status'=>0, 'msg'=> __('adminWords.error_msg') ];
            }
        }else{
           $resp = $checkValidate;
        }
       echo json_encode($resp);
    }
    
    public function createCoupon(){
        $data['plan'] = Plan::where('status', 1)->pluck('plan_name', 'id')->all();
        return view('coupon::addEdit', $data);
    }

    public function editCoupon($id){
        $data['couponData'] = Coupon::find($id);
        $data['plan'] = Plan::where('status', 1)->pluck('plan_name', 'id')->all();
        return view('coupon::addEdit', $data);
    }

    function updateCouponStatus(Request $request, $id){
        $checkValidate = validation($request->all(),['status' =>'required']);
        if($checkValidate['status'] == 1){
            $resp = change_status(['table'=>'coupons', 'column'=>'id', 'where'=>['id'=>$id],'data'=> ['status'=>$request->status]]);
            echo $resp;
        }else{
            echo json_encode($checkValidate);
        }
    }

    public function destroyCoupon($id){
        $resp = singleDelete([ 'table'=>'coupons','column'=>['coupon_code'], 'where'=>['id'=>$id], 'msg'=> __('adminWords.coupon').' '.__('adminWords.delete_success')]);
        echo $resp;        
    }

    function bulkDeleteCoupon(Request $request){
        $checkValidate = validation($request->all(),['checked' =>'required'], __('adminWords.atleast').' '.__('adminWords.coupon').' '.__('adminWords.must_selected') );
        if($checkValidate['status'] == 1){
            $resp = bulkDeleteData(['table'=>'coupons','column'=>'id', 'msg'=>__('adminWords.coupon').' '.__('adminWords.delete_success'), 'request'=>$request->except('_token')]);
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }
}
