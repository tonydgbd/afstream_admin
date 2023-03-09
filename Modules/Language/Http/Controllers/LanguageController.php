<?php
namespace Modules\Language\Http\Controllers;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use DataTables;
use Modules\Language\Entities\Language;
use Illuminate\support\Carbon;

class LanguageController extends Controller
{
    public function languages(){
        return view('language::index');
    }

    public function languageData(){
        $language = select(['table'=>'languages','column'=>'*','order'=>['id','desc']]);
        return DataTables::of($language)
        ->editColumn('checkbox',function($language){
            return $language->is_default == 0 ? '<div class="checkbox danger-check"><input name="checked" id="checkboxAll'.$language->id.'" type="checkbox" class="CheckBoxes" value="'.$language->id.'"><label for="checkboxAll'.$language->id.'"></label></div>' : '';
             ;
        })
        ->editColumn('language_name', function($language){
            return ucfirst($language->language_name).' '.($language->is_default == 1 ? '<span class="badge badge-success">Default</span>' : '');
        })
        ->editColumn('created_at', function($language){
            return date('d-m-Y', strtotime($language->created_at));
        })
        ->editColumn('status', function($language){
            return '<div class="checkbox success-check"><input id="checkboxc'.$language->id.'" name="status" class="updateStatus" '.($language->status == 1 ? 'checked':'').' type="checkbox" data-url="'.url('language/status/'.$language->id).'"><label for="checkboxc'.$language->id.'"></label></div>';
        })
        ->addColumn('action', function ($language){
            $delete = $language->is_default == 0 ? '<a href="javascript:void(0); data-url="'.url('language/addEdit/'.$language->id).'" class="mr-2" id="deleteRecordById"><i class="far fa-trash-alt mr-2  mr-2"></i>'.__('adminWords.delete').'</a>' : '';
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
                            <a class="languagePopupToggle" data-url="'.url('language/data/'.$language->id).'" data-save="'.url('language/addEdit/'.$language->id).'"><i class="far fa-edit mr-2 "></i>'.__('adminWords.edit').'</a>
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
        $lang = Language::find($id);
        $resp = !empty($lang) ? ['status'=>1, 'data'=>$lang] :  ['status'=>0, 'msg'=>__('adminWords.error_msg')];
        echo json_encode($resp);
    }

    public function updateLanguageStatus(Request $request, $id){ 
        $checkValidate = validation($request->all(),['status' =>'required']);
        if($checkValidate['status'] == 1){
            $resp = change_status(['table'=>'languages', 'column'=>'id', 'where'=>['id'=>$id],'data'=> ['status'=>$request->status]]);
            echo $resp;
        }else{
            echo json_encode($checkValidate);
        }
    }

    public function addEditLanguage(Request $request, $id){
        $checkValidate = validation($request->except('_token'), 
        [
            'language_name' => 'required',
            'language_code'=>'required|min:2|max:2'
        ]);
        if($checkValidate['status'] == 1){
            $arr = [
                'language_name' => $request->language_name,
                'language_code' => $request->language_code,
                'status' => isset($request->status) ? '1' : '0',
                'is_default' => isset($request->is_default) ? '1' : '0'
            ];
            $where = is_numeric($id) ? [['id','!=',$id], ['language_name','=', $arr['language_name']] ] : [['language_name','=', $arr['language_name']] ];
            $language = Language::where($where)->get();
            if(count($language) > 0){
                $resp = ['status'=>0, 'msg'=>__('adminWords.language').' '.__('adminWords.already_exist')];
            }else{
                if($arr['is_default'] == 1)
                    $updateDefaultNone = Language::where('is_default',1)->update(['is_default'=>0]);

                $lang = is_numeric($id) ? Language::find($id) : [];
                if(!empty($lang)){
                    $lang->update($arr);
                    $msg = __('adminWords.language').' '.__('adminWords.updated_msg');
                }else{
                    Language::create($arr);
                    $msg = __('adminWords.language').' '.__('adminWords.added_msg');
                }
                if($arr['is_default'] == 1){
                    $resp = ['status'=>1, 'msg'=>$msg, 'redirect_url' => url('locale/'.$request->language_code)];
                }else{
                    $resp = ['status'=>1, 'msg'=>$msg];
                }             
            }
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

    public function destroyLanguage($id){
        $resp = singleDelete([ 'table'=>'languages','column'=>'id','where'=>['id'=>$id], 'msg'=>__('adminWords.language').' '.__('adminWords.delete_success')]);
        echo $resp;
    }

    public function bulkDeleteLanguage(Request $request){
        $checkValidate = validation($request->all(),['checked' =>'required'],__('adminWords.atleast').' '.__('adminWords.language').' '.__('adminWords.must_selected'));
        if($checkValidate['status'] == 1){
            $resp = bulkDeleteData(['table'=>'languages', 'column'=>'id', 'msg'=>__('adminWords.language').' '.__('adminWords.delete_success'),'request'=>$request->except('_token')]);
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }
}
