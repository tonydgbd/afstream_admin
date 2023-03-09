<?php

namespace Modules\Album\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use DataTables;
use Modules\Album\Entities\Album;
use Modules\Audio\Entities\Audio;
use Illuminate\Support\Carbon;
use Str;
use Modules\Language\Entities\Language;

class AlbumController extends Controller
{
    public function index(){
        return view('album::index');
    } 

    public function albumData(){
        $albums = select(['column' => ['albums.id','albums.image','albums.album_name','albums.copyright','albums.song_list','albums.is_album_movie', 'albums.status', 'albums.created_at'], 'table' => 'albums', 'order'=>['id','desc'] ]);
        return DataTables::of($albums)
            ->editColumn('checkbox',function($albums){
                return '<div class="checkbox danger-check"><input name="checked" id="checkboxAll'.$albums->id.'" type="checkbox" class="CheckBoxes" value="'.$albums->id.'"><label for="checkboxAll'.$albums->id.'"></label></div>';
            })
            ->editColumn('image', function($albums){
                if($albums->image != '' && file_exists(public_path('/images/album/'.$albums->image)))
                    $src = asset('public/images/album/'.$albums->image);
                else
                    $src = asset('public/images/sites/500x500.png');
                return '<span class="img-thumb"><img src="'.$src.'" alt="" class="img-fluid" width="60px" height="60px"></span>';
            })
            ->editColumn('album_movie', function($albums){
                return ($albums->is_album_movie == 1) ? __('adminWords.album') : __('adminWords.movie');
            })
            ->editColumn('created_at', function($albums){ 
                return date('d-m-Y', strtotime($albums->created_at));
            })
            ->editColumn('status', function($albums){
                return '<div class="checkbox success-check"><input id="checkboxc'.$albums->id.'" name="status" class="custom-control-input updateStatus" '.($albums->status == 1 ? 'checked':'').' type="checkbox" data-url="'.url('albums/status/'.$albums->id).'"><label for="checkboxc'.$albums->id.'"></label></div>';
            })
            ->addColumn('action', function ($albums) {
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
                            <a class="audioGenrePopupToggle" href="'.url('albums/edit/'.$albums->id).'"><i class="far fa-edit mr-2 "></i>'.__('adminWords.edit').'</a>
                        </li>
                        <li>
                            <a href="javascript:void(0); " data-url="'.url('albums/destroy/'.$albums->id).'" id="deleteRecordById"><i class="far fa-trash-alt mr-2 "></i>'.__('adminWords.delete').'</a>
                        </li>
                    </ul>
                </div>';
            })
            ->rawColumns(['checkbox','image','status','action'])->make(true);
    }

    public function addEditAlbum(Request $request, $id){
        $rules = [ 'album_name' => 'required','song_list' => 'required', 'language_id' => 'required' ];
        if(!is_numeric($id)){
            $rules['image'] = 'required|mimes:jpg,jpeg,png|max:2048';
        }
        $checkValidate = validation($request->except('_token'), $rules);
        if($checkValidate['status'] == 1){
            $slug = Str::slug($request->album_name,'-');
            $where = is_numeric($id) ? [['id','!=',$id],['album_slug','=',$slug]] : [['album_slug','=',$slug]];
            $checkAlbum = Album::where($where)->first();
            if(!empty($checkAlbum) > 0){
                $resp = array('status'=>0, 'msg'=> __('adminWords.album').' '.__('adminWords.already_exist'));
            }else{
                $checkAlbum = is_numeric($id) ? Album::find($id) : [];
                $data = $request->except('_token');
                $data['song_list'] = json_encode($request->song_list);
                $data['album_slug'] = $slug;
                $data['status'] = isset($request->status) ? 1 : 0;
                $data['language_id'] = $request->language_id;
                $data['is_album_movie'] = $request->is_album_movie;
                $data['is_featured'] = isset($request->is_featured) ? 1 : 0;
                $data['is_trending'] = isset($request->is_trending) ? 1 : 0;
                $data['is_recommended'] = isset($request->is_recommended) ? 1 : 0;
                $data['is_verified'] = isset($request->is_verified) ? 1 : 0;
                
                if($image = $request->file('image')){
                    $name = 'album-'.time().'.webp';
                    $data['image'] = str_replace(' ','',$name);
                    upload_image($image, public_path().'/images/album/', $name, '500x500');
                    if(!empty($checkAlbum) && $checkAlbum->image != ''){
                        delete_file_if_exist(public_path().'/images/album/'.$checkAlbum->image);
                    }
                }
                
                $addAlbum = empty($checkAlbum) ? Album::create($data) : $checkAlbum->update($data);
                $resp = ($addAlbum) ? ['status'=>1, 'msg'=> __('adminWords.album').' '.__('adminWords.success_msg')] : ['status'=>0, 'msg'=> __('adminWords.error_msg') ];
            }
        }else{
           $resp = $checkValidate;
        }
       echo json_encode($resp);
    }
    
    public function createAlbum(){
        $data['albumData'] = [];
        $data['song_list'] = Audio::where('status', 1)->orderBy('id', 'desc')->get();
        $data['language'] = Language::where('status',1)->pluck('language_name','id')->all();
        return view('album::addEdit', $data);
    }

    public function editAlbum($id){
        $albumData = Album::where('id',$id)->get();
        if(sizeof($albumData) > 0 ){
            $data['albumData'] = $albumData[0];
            $data['song_list'] = Audio::where('status', 1)->orderBy('id', 'desc')->get();
            $data['language'] = Language::where('status',1)->pluck('language_name','id')->all();
            return view('album::addEdit', $data);
        }else{
            return redirect('album');
        }
    }

    function updateAlbumStatus(Request $request, $id){
        $checkValidate = validation($request->all(),['status' =>'required']);
        if($checkValidate['status'] == 1){
            $resp = change_status(['table'=>'albums', 'column'=>'id', 'where'=>['id'=>$id],'data'=> ['status'=>$request->status]]);
            echo $resp;
        }else{
            echo json_encode($checkValidate);
        }
    }

    public function destroyAlbum($id){
        $resp = singleDelete([ 'table'=>'albums','column'=>['image','album_name'], 'where'=>['id'=>$id], 'msg'=> __('adminWords.album').' '.__('adminWords.delete_success'), 'isImage'=>public_path().'/images/album/' ]);
        echo $resp;        
    }

    function bulkDeletePlanData(Request $request){
        $checkValidate = validation($request->all(),['checked' =>'required'], __('adminWords.atleast').__('adminWords.plan').__('adminWords.must_selected'));
        if($checkValidate['status'] == 1){
            $resp = bulkDeleteData(['table'=>'plans','column'=>'id', 'msg'=>__('adminWords.plan').' '.__('adminWords.delete_success'), 'request'=>$request->except('_token')]);
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }
    
    public function getAlbumRecordbylanguage(Request $request){
        
        $audios = [];
        $id = $request->getLanguage;
        $audios = Audio::select('id','audio_title')->where(['audio_language'=> $id,'status'=>1])->get()->toArray();
        
        if(!empty($audios)){
            $resp = ['status'=>1, 'data'=>$audios];
        }else{
            $resp = ['status'=>0, 'data'=>$audios];
        }
        echo json_encode($resp);
    }
    
}
