<?php

namespace Modules\General\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use DataTables;
use Modules\Audio\Entities\Audio;
class GeneralController extends Controller{

    public function faqs(){
       return view('general::faq.index');
    }

    public function faqsData(){
        $faqs = select(['table'=>'faq','column'=>'*']);
        if(!empty($faqs)){
            $faqArr = [];
            foreach($faqs as $faq){
                $audio_id = $faq->audio_id;
                $getAudio = select(['table'=>'audio','column'=>'name','where'=>['id'=>$audio_id], 'single'=>'1']);
                array_push($faqArr, ['id'=>$faq->id, 'title'=>$faq->title, 'detail'=>$faq->details, 'audio_name'=>$getAudio->name, 'status'=>$faq->status]);
            }
            return DataTables::of($faqArr)
            ->editColumn('checkbox', function($faq){
                return '<div class="inline custom-checkbox"><input id="checkboxAll'.$faq['id'].'" type="checkbox" class="custom-control-input CheckBoxes faqCheckBox" value="'.$faq['id'].'"><label for="checkboxAll'.$faq['id'].'" class="custom-control-label"></label></div>';
            })
            ->addColumn('title', function($faq){
                return $faq['title'];
            })
            ->addColumn('detail', function($faq){
                return $faq['detail'];
            })
            ->addColumn('audio_name', function($faq){
                return $faq['audio_name'];
            })
            ->editColumn('status', function($faq){
                return '<div class="custom-switch"><input id="switch3" name="status" class="custom-control-input updateStatus" '.($faq['status'] == 1 ? 'checked':'').' type="checkbox" data-url="'.url('updateFaqStatus/'.$faq['id']).'"><label class="custom-control-label" for="switch3"></label></div>';
            })
            ->editColumn('action', function($faq){
                return '<div class="button-list"><a href="'.url('updateFaq/'.$faq['id']).'" class="btn btn-sm btn-success"><i class="feather icon-edit-2"></i></a><button type="button" data-url="'.url('destroyFaq/'.$faq['id']).'" class="btn btn-sm btn-danger" id="deleteRecordById"><i class="feather icon-trash"></i></button></div>';
            })
            ->rawColumns(['checkbox','status','action'])->make(true);
        }
    }

    public function updateFaq($id){
        $data['audio'] = Audio::where('status',1)->pluck('name','id')->all();
        $faq = select(['table'=>'faq','column'=>['id','title','details','status','audio_id'], 'where'=>['id'=>$id]]);
        $data['faqs'] = $faq[0];
        return view('general::faq.create', $data);
    }


    public function addFaq(){
        $data['audio'] = Audio::where('status',1)->pluck('name','id')->all();
        return view('general::faq.create', $data);
    }

    public function updateFaqStatus($id){
        $resp = change_status(['table'=>'faq', 'column'=>'id', 'where'=>['id'=>$id],'data'=> ['status'=>$request->status]]);
        echo $resp;
    }

    public function addUpdateFaq(Request $request, $id){
        $faqs = [];
        if(is_numeric($id)){
            $faqs = select(['table'=>'faq','column'=>'id','where'=>['id'=>$id]]);
        }
        $data = $request->except('_token');
        $data['status'] = (isset($data['status']) ? 1 : 0);
        
        $rules = [
            'title'=>'required',
            'audio_id'=>'required',
            'details'=>'required'
        ];
        $checkValidation = validation($data, $rules);
        if($checkValidation['status'] == 1){
            if(!empty($faqs)){
                $update = update(['table'=>'faq','where'=>['id'=>$id], 'data'=>$data]);
                $msg = 'Data updated successfully.';
            }else{
                $update = insert(['table'=>'faq', 'data'=>$data]);
                $msg = 'Data saved successfully.';
            }
            $resp = ['status'=>1, 'msg'=>$msg];
        }else{
            $resp = $checkValidation;
        }
        echo json_encode($resp);        
    }

    public function destroyFaq($id){
        $resp = singleDelete([ 'table'=>'faq','column'=>'id','where'=>['id'=>$id], 'msg'=>'Faq deleted successfully.']);
        echo $resp;
    }

    public function bulkDelete(Request $request){
        $checkValidate = validation($request->all(),['checked' =>'required'],'Atleast 1 Faq must be selected.');
        if($checkValidate['status'] == 1){
            $resp = bulkDeleteData(['table'=>'faq', 'column'=>'id', 'msg'=>'Faq deleted successfully.','request'=>$request->except('_token')]);
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

    public function blogs(){
        return view('general::blog.index');
    }

}
