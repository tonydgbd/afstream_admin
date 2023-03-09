<?php
namespace Modules\Plan\Http\Controllers;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Plan\Entities\Plan;
use DataTables;
use Illuminate\Support\Carbon;

class PlanController extends Controller{
   
    public function index(){
        return view('plan::index');
    }

    public function planData(){
        $plans = select(['column'=>'*','table'=>'plans','order'=>['id','desc']]);
        return DataTables::of($plans)
            ->editColumn('checkbox',function($plans){
                return '<div class="checkbox danger-check"><input name="checked" id="checkboxAll'.$plans->id.'" type="checkbox" class="CheckBoxes" value="'.$plans->id.'"><label for="checkboxAll'.$plans->id.'"></label></div>';
            })
            ->editColumn('image', function($plans){
                if($plans->image != '' && file_exists(public_path('/images/plan/'.$plans->image)))
                    $src = asset('public/images/plan/'.$plans->image);
                else
                    $src = asset('public/images/sites/500x500.png');
                return '<img src="'.$src.'" alt="" class="img-fluid" width="60px" height="60px">';
            })
            ->editColumn('validity', function($plans){
                return $plans->is_month_days == 0 ? $plans->validity.' Days' : $plans->validity.' Months';
            })
            ->editColumn('plan_amount', function($plans){
                return '$'.$plans->plan_amount;
            })
            ->editColumn('is_download', function($plans){
                return '<div class="checkbox success-check"><input id="checkboxc_'.$plans->id.'" name="is_download" class="changePlanDataVal" '.($plans->is_download == 1 ? 'checked':'').' data-field="is_download" type="checkbox" data-url="'.url('plan/is_download/'.$plans->id).'"><label for="checkboxc_'.$plans->id.'"></label></div>';
            })
            ->editColumn('show_advertisement', function($plans){
                return '<div class="checkbox success-check"><input id="checkboxc-'.$plans->id.'" name="show_advertisement" class="changePlanDataVal" '.($plans->show_advertisement == 1 ? 'checked':'').' data-field="show_advertisement" type="checkbox" data-url="'.url('plan/show_adv/'.$plans->id).'"><label for="checkboxc-'.$plans->id.'"></label></div>';
            })
            ->editColumn('created_at', function($plans){
                return date('d-m-Y', strtotime($plans->created_at));
            })
            ->editColumn('status', function($plans){
                return '<div class="checkbox success-check"><input id="checkboxc'.$plans->id.'" name="status" class="updateStatus" '.($plans->status == 1 ? 'checked':'').' type="checkbox" data-url="'.url('plan/status/'.$plans->id).'"><label for="checkboxc'.$plans->id.'"></label></div>';
            })
            ->addColumn('action', function ($plans) {
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
                            <a class="blogCategoryPopupToggle" href="'.url('plan/edit/'.$plans->id).'"><i class="far fa-edit mr-2 "></i>'.__('adminWords.edit').'</a>
                        </li>
                        <li>
                            <a href="javascript:void(0); " data-url="'.url('plan/destroy/'.$plans->id).'" id="deleteRecordById"><i class="far fa-trash-alt mr-2 "></i>'.__('adminWords.delete').'</a>
                        </li>
                    </ul>
                </div>';
            })
            ->rawColumns(['checkbox','image','is_download','show_advertisement','status','action'])->make(true);
    }

    public function create(){
        return view('plan::addEdit');
    }

    public function addEditPlan(Request $request, $id){
        $rules = [ 'plan_name' => 'required','validity' => 'required|numeric|gt:0', 'plan_amount' => 'required' ];
        $rules['image'] = (!is_numeric($id) ? 'required|mimes:jpg,jpeg,png|max:2048' : '');

        $checkValidate = validation($request->except('_token'), $rules);
        if($checkValidate['status'] == 1){
            
            $where = is_numeric($id) ? [['id','!=',$id],['plan_name','=',$request->plan_name]] : [['plan_name','=',$request->plan_name]];
            $checkPlan = Plan::where($where)->first();
            if(!empty($checkPlan) > 0){
                $resp = array('status'=>0, 'msg'=> __('adminWords.plan').' '.('adminWords.already_exist') );
            }else{
                $checkPlan = is_numeric($id) ? Plan::find($id) : [];
                $data = $request->except('_token');
                $data['is_download'] = isset($request->is_download) ? 1 : 0;
                $data['show_advertisement'] = isset($request->show_advertisement) ? 1 : 0;
                $data['status'] = isset($request->status) ? 1 : 0;
                $data['is_month_days'] = $request->month_days;
                
                if(isset($request->in_app_purchase) && !empty($request->in_app_purchase) && $request->in_app_purchase == 1){
                    $data['in_app_purchase'] = '1';
                    $data['product_id'] = $request->product_id;
                }else{
                    $data['in_app_purchase'] = '0';
                    $data['product_id'] = '';
                }
                
                if($image = $request->file('image')){
                    $name = 'plan-'.time().'.webp';
                    $data['image'] = str_replace(' ','',$name);
                    upload_image($image, public_path().'/images/plan/', $name, '500x500');                    
                    if(!empty($checkPlan) && $checkPlan->image != ''){
                        delete_file_if_exist(public_path().'/images/plan/'.$checkPlan->image);
                    }
                }
                
                $addPlan = empty($checkPlan) ? Plan::create($data) : $checkPlan->update($data);
                $resp = ($addPlan) ? ['status'=>1, 'msg'=> __('adminWords.plan').' '.__('adminWords.success_msg')] : ['status'=>0, 'msg'=> __('adminWords.error_msg') ];
            }
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

    public function edit($id){
        $plans = Plan::where('id',$id)->get();
        $data['planData'] = !empty($plans) ? $plans[0] : [];
        return view('plan::addEdit',$data);
    }
    
    public function destroyPlan($id){
        $resp = singleDelete([ 'table'=>'plans','column'=>['image','plan_name'], 'where'=>['id'=>$id], 'msg'=> __('adminWords.plan').' '.__('adminWords.delete_success'), 'isImage'=>public_path().'/images/plan/' ]);
        echo $resp;        
    }

    function bulkDeletePlanData(Request $request){
        $checkValidate = validation($request->all(),['checked' =>'required'], __('adminWords.atleast').' '.__('adminWords.plan').' '.__('adminWords.must_selected') );
        if($checkValidate['status'] == 1){
            $resp = bulkDeleteData(['table'=>'plans','column'=>'id', 'msg'=> __('adminWords.plan').' '.__('adminWords.delete_success'), 'request'=>$request->except('_token')]);
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

    function updatePlanStatus(Request $request, $id){
        $checkValidate = validation($request->all(),['status' =>'required']);
        if($checkValidate['status'] == 1){
            $resp = change_status(['table'=>'plans', 'column'=>'id', 'where'=>['id'=>$id],'data'=> ['status'=>$request->status]]);
            echo $resp;
        }else{
            echo json_encode($checkValidate);
        }
    }

    function updateDownloadStatus(Request $request, $id){
        $checkValidate = validation($request->all(),['is_download' =>'required']);
        if($checkValidate['status'] == 1){
            $resp = change_status(['table'=>'plans', 'column'=>'id', 'where'=>['id'=>$id],'data'=> ['is_download'=>$request->is_download], 'msg' => __('adminWords.data').' '.__('adminWords.success_msg') ]);
            echo $resp;
        }else{
            echo json_encode($checkValidate);
        }
    }

    function updateAdvStatus(Request $request, $id){
        $checkValidate = validation($request->all(),['show_advertisement' =>'required']);
        if($checkValidate['status'] == 1){
            $resp = change_status(['table'=>'plans', 'column'=>'id', 'where'=>['id'=>$id],'data'=> ['show_advertisement'=>$request->show_advertisement], 'msg' => __('adminWords.data').' '.__('adminWords.success_msg') ]);
            echo $resp;
        }else{
            echo json_encode($checkValidate);
        }
    }

}
