<?php

namespace Modules\AudioLanguage\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\AudioLanguage\Entities\AudioLanguage; 
use DataTables;
use Illuminate\support\Carbon;

class AudioLanguageController extends Controller
{
    
    public function audioLanguages(){
       
        return view('audiolanguage::index');
    }

    public function audioLanguageData(){

        $language = select(['table'=>'audio_languages','column'=>'*','order'=>['id','desc']]);
        return DataTables::of($language)
        ->editColumn('checkbox',function($language){
            return '<div class="checkbox danger-check"><input name="checked" id="checkboxAll'.$language->id.'" type="checkbox" class="CheckBoxes" value="'.$language->id.'"><label for="checkboxAll'.$language->id.'"></label></div>';
            
        })
        ->editColumn('language_name', function($language){
            return ucfirst($language->language_name);
        })
        ->editColumn('created_at', function($language){
            return date('d-m-Y', strtotime($language->created_at));
        })
        ->editColumn('status', function($language){
            return '<div class="checkbox success-check"><input id="switch'.$language->id.'" name="status" class="updateStatus" '.($language->status == 1 ? 'checked':'').' type="checkbox" data-url="'.url('audio_language/status/'.$language->id).'"><label for="switch'.$language->id.'"></label></div>';
        })
        ->addColumn('action', function ($language){
            $delete = '<a href="javascript:void(0)" data-url="'.url('audio_language/destroy/'.$language->id).'" id="deleteRecordById"><i class="far fa-trash-alt mr-2  mr-2"></i>'.__('adminWords.delete').'</a>';
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
                            <a class="languagePopupToggle" data-url="'.url('audio_language/data/'.$language->id).'" data-save="'.url('audio_language/addEdit/'.$language->id).'"><i class="far fa-edit mr-2 "></i>'.__('adminWords.edit').'</a>
                        </li>                        
                        <li>
                            '.$delete.'
                        </li>
                    </ul>
                </div>';
        })
        ->rawColumns(['checkbox','status','action', 'language_name'])->make(true);
    }

    public function getLanguageData($id){
 
        $lang = AudioLanguage::find($id);
        $resp = !empty($lang) ? ['status'=>1, 'data'=>$lang] :  ['status'=>0, 'msg'=>__('adminWords.error_msg')];
        echo json_encode($resp);
    }

    public function updateLanguageStatus(Request $request, $id){ 

        $checkValidate = validation($request->all(),['status' =>'required']);
        if($checkValidate['status'] == 1){
            $resp = change_status(['table'=>'audio_languages', 'column'=>'id', 'where'=>['id'=>$id],'data'=> ['status'=>$request->status]]);
            echo $resp;
        }else{
            echo json_encode($checkValidate);
        }
    }

    public function addEditLanguage(Request $request, $id){

        
        $checkValidate = validation($request->except('_token'), 
        [
            'language_name' => 'required',
            'language_code'=>'required|min:2|max:2',            
        ]);

        if($checkValidate['status'] == 1){

            $arr = [
                'language_name' => $request->language_name,
                'language_code' => $request->language_code,
                'status' => isset($request->status) ? '1' : '0',
            ];

            if($image = $request->file('image')){
                $name = 'language-'.time().'.webp';
                $arr['image'] = str_replace(' ','',$name);
                upload_image($image, public_path().'/images/language/', $name, '500x500');        //'images/language/'             
            }

            $where = is_numeric($id) ? [['id','!=',$id], ['language_name','=', $arr['language_name']] ] : [['language_name','=', $arr['language_name']] ];
            $language = AudioLanguage::where($where)->get();
            if(count($language) > 0){
                $resp = ['status'=>0, 'msg'=>__('adminWords.audio').' '.__('adminWords.language').' '.__('adminWords.already_exist')];
            }else{

                $lang = is_numeric($id) ? AudioLanguage::find($id) : [];
                if(!empty($lang)){
                    $lang->update($arr);
                    $msg = __('adminWords.audio').' '.__('adminWords.language').' '.__('adminWords.updated_msg');
                }else{
                    AudioLanguage::create($arr);
                    $msg = __('adminWords.audio').' '.__('adminWords.language').' '.__('adminWords.added_msg');
                }
            }
            
            $resp = ['status'=>1, 'msg'=>$msg];
                  
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

    public function destroyLanguage($id){

        $resp = singleDelete([ 'table'=>'audio_languages','column'=>'id','where'=>['id'=>$id], 'msg'=>__('adminWords.language').' '.__('adminWords.delete_success')]);
        echo $resp;
    }

    public function bulkDeleteLanguage(Request $request){

        $checkValidate = validation($request->all(),['checked' =>'required'],__('adminWords.atleast').' '.__('adminWords.language').' '.__('adminWords.must_selected'));
        if($checkValidate['status'] == 1){
            $resp = bulkDeleteData(['table'=>'audio_languages', 'column'=>'id', 'msg'=>__('adminWords.language').' '.__('adminWords.delete_success'),'request'=>$request->except('_token')]);
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }
}
