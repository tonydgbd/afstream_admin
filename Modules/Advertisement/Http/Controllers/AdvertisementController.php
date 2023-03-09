<?php

namespace Modules\Advertisement\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Advertisement\Entities\Advertisement;
use DataTables;
use Illuminate\Support\Carbon;

class AdvertisementController extends Controller{

    public function index(){
        return view('advertisement::index');
    }

    public function createAdv(){
        return view('advertisement::addEdit');
    }

    public function addEditAdv(Request $request){
        $checkValidate = validation($request->except('_token'), ['google_adsense_script' => 'required']);
        if($checkValidate['status'] == 1){
            $adv = Advertisement::create(['google_adsense_script' => $request->google_adsense_script, 'title' => $request->title, 'status' => 1]);
            $resp = ['status' => 1, 'msg' => __('adminWords.adv').' '.__('adminWords.success_msg') ];
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

    public function advData(){
        $adv = select(['column'=>'*','table'=>'advertisements','order'=>['id','desc']]);
        return DataTables::of($adv)
            ->editColumn('checkbox',function($adv){
                return '<div class="checkbox danger-check"><input name="checked" id="checkboxAll'.$adv->id.'" type="checkbox" class="CheckBoxes" value="'.$adv->id.'"><label for="checkboxAll'.$adv->id.'"></label></div>';
            })
            ->editColumn('created_at', function($adv){
                return date('d-m-Y', strtotime($adv->created_at));
            })
            ->editColumn('status', function($adv){
                return '<div class="checkbox success-check"><input id="checkboxc'.$adv->id.'" name="status" class="updateStatus" '.($adv->status == 1 ? 'checked':'').' type="checkbox" data-url="'.url('adv/status/'.$adv->id).'"><label for="checkboxc'.$adv->id.'"></label></div>';
            })
            ->addColumn('action', function ($adv) {
                return '<div class="button-list"><a class="btn btn-sm btn-success mr-2" href="'.url('adv/edit/'.$adv->id).'"><i class="far fa-edit"></i></a><button type="button" data-url="'.url('adv/destroy/'.$adv->id).'" class="btn btn-sm btn-danger" id="deleteRecordById"><i class="far fa-trash-alt mr-2 "></i></button></div>';
            })
            ->rawColumns(['checkbox','image','is_download','show_advertisement','status','action'])->make(true);
    }

    public function editAdv($id){
        $data['advData'] = Advertisement::find($id);
        return view('advertisement::addEdit',$data);
    }
    
    public function destroyAdv($id){
        $resp = singleDelete([ 'table'=>'advertisements','column'=>['id'], 'where'=>['id'=>$id], 'msg'=> __('adminWords.adv').' '.__('adminWords.delete_success') ]);
        echo $resp;        
    }

    function bulkDeleteAdvData(Request $request){
        $checkValidate = validation($request->all(),['checked' =>'required'], __('adminWords.atleast').__('adminWords.adv').__('adminWords.must_selected'));
        if($checkValidate['status'] == 1){
            $resp = bulkDeleteData(['table'=>'advertisements','column'=>'id', 'msg'=>__('adminWords.adv').' '.__('adminWords.delete_success'), 'request'=>$request->except('_token')]);
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

    function updateAdvStatus(Request $request, $id){
        $checkValidate = validation($request->all(),['status' =>'required']);
        if($checkValidate['status'] == 1){
            $resp = change_status(['table'=>'advertisements', 'column'=>'id', 'where'=>['id'=>$id],'data'=> ['status'=>$request->status]]);
            echo $resp;
        }else{
            echo json_encode($checkValidate);
        }
    }
}
