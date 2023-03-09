<?php

namespace Modules\AdminPlaylist\Http\Controllers; 

use Modules\AdminPlaylist\Entities\AdminPlaylistGenre;
use Modules\AudioLanguage\Entities\AudioLanguage; 
use Modules\AdminPlaylist\Entities\AdminPlaylist;
use Illuminate\Contracts\Support\Renderable;
use Modules\Artist\Entities\Artist;
use Illuminate\Routing\Controller;
use Modules\Album\Entities\Album;
use Modules\Audio\Entities\Audio;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use DataTables;
use Crypt;
use Auth;
use Str;

class AdminPlaylistController extends Controller
{
    public function index(){
        return view('adminplaylist::playlist.index'); 
    }

    public function playlistData(){
                
        $playlists = select(['column' => ['admin_playlists.*', 'audio_languages.language_name as audio_language'], 'table' => 'admin_playlists', 'order'=>['id','desc'], 'join' => [['audio_languages','audio_languages.id','=','admin_playlists.audio_language']] ]);       

        return DataTables::of($playlists)
            ->editColumn('checkbox',function($playlists){
                return '<div class="checkbox danger-check"><input name="checked" id="checkboxAll'.$playlists->id.'" type="checkbox" class="CheckBoxes" value="'.$playlists->id.'"><label for="checkboxAll'.$playlists->id.'" class="custom-control-label"></label></div>';
            })

            ->editColumn('created_at', function($playlists){ 
                return date('d-m-Y', strtotime($playlists->created_at));
            })
            ->editColumn('status', function($playlists){
                return '<div class="checkbox success-check"><input id="checkboxc'.$playlists->id.'" name="status" class="updateStatus" '.($playlists->status == 1 ? 'checked':'').' type="checkbox" data-url="'.url('admin/playlist/status/'.$playlists->id).'"><label for="checkboxc'.$playlists->id.'"></label></div>';
            })            

            ->addColumn('action', function ($playlists){
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
                            <a class="audioGenrePopupToggle" href="'.url('admin/playlist/edit/'.$playlists->id).'"><i class="far fa-edit mr-2 "></i>'.__('adminWords.edit').'</a>
                        </li>
                        <li>
                            <a href="javascript:void(0); " data-url="'.url('admin/playlist/destroy/'.$playlists->id).'" id="deleteRecordById"><i class="far fa-trash-alt mr-2 "></i>'.__('adminWords.delete').'</a>
                        </li>
                    </ul>
                </div>';
            })
            ->rawColumns(['checkbox','status','action'])->make(true);
    }

    public function create(){
        
        $data['album'] = Album::select('album_name','id','language_id')->where('status','1')->get()->toArray(); 
        $data['artist'] = Artist::select('artist_name','id','audio_language_id')->where('status','1')->get()->toArray(); 
        $data['song_list'] = Audio::where('status', 1)->orderBy('id', 'desc')->where('status','1')->get();
        return view('adminplaylist::playlist.addEdit', $data);   
    }
    
    public function getRecordbylanguage($id){     
        
        $data = [];
        $data['album'] = Album::select('album_name','id','language_id')->where(['language_id'=> $id,'status' => '1'])->get()->toArray(); 
        $artistLngId =  json_encode(array($id));
        $data['artist'] = Artist::select('artist_name','id','audio_language_id')->where(['audio_language_id' => $artistLngId,'status' => '1'])->get()->toArray(); 
        $data['song_list'] = Audio::where('status', 1)->orderBy('id', 'desc')->where(['audio_language'=> $id,'status' => '1'])->get();
        if(!empty($data)){
            $resp = ['status'=>1, 'data'=>$data];
        }else
            $resp = ['status'=>0, 'data'=>$data];
        echo json_encode($resp);
    }
    
    
    public function edit($id){
        
        $data['album'] = Album::select('album_name','id','language_id')->where('status','1')->get()->toArray(); 
        $data['artist'] = Artist::select('artist_name','id','audio_language_id')->where('status','1')->get()->toArray(); 
        $data['song_list'] = Audio::where('status', 1)->orderBy('id', 'desc')->where('status','1')->get();       
        
        $data['audioData'] = AdminPlaylist::find($id);        
        if(!empty($data['audioData'])){            
            return view('adminplaylist::playlist.addEdit', $data);
        }else{
            return redirect()->back();
        }
    }

    public function addEditPlaylist(Request $request, $id){
        
        if($request->ajax()){
            
            $playlistData = [];
            $rules = [
                'playlist_title' => 'required',
                'audio_language' => 'required',
            ];
            
            $checkValidate = validation($request->except('_token'), $rules);
            if($checkValidate['status'] == 1){
                $slug = Str::slug($request->playlist_title,'-');
    
                $where = is_numeric($id) ? [['id','!=',$id],['playlist_title_slug','=',$slug]] : [['playlist_title_slug','=',$slug]];
    
    
                $checkPlaylist = AdminPlaylist::where($where)->first();
                if(!empty($checkPlaylist)){
                    $resp = array('status'=>2, 'msg'=>__('frontWords.playlist').' '.__('adminWords.already_exist'));
                }else{
                    $playlistCheck = is_numeric($id) ? AdminPlaylist::find($id) : [];
                    $playlistData = $request->except('_token');
    
                    $playlistData['playlist_title'] = $request->playlist_title;
                    $playlistData['playlist_title_slug'] = $slug;
                    $playlistData['audio_language'] = $request->audio_language;
                    $playlistData['artist_id'] = isset($request->artist_id) ? json_encode($request->artist_id) : '';
                    $playlistData['album_id'] = isset($request->album_id) ? json_encode($request->album_id) : '';
                    $playlistData['audio_id'] = isset($request->audio_id) ? json_encode($request->audio_id) : '';
                    $playlistData['status'] = isset($request->status) ? 1 : 0;                               
                    
                    $addPlaylist = !empty($playlistCheck) ? $playlistCheck->update($playlistData) : AdminPlaylist::create($playlistData);
                    $resp = ($addPlaylist) ? ['status'=>1, 'msg'=> __('frontWords.playlist').' '.__('adminWords.success_msg')] : ['status'=>2, 'msg'=> __('adminWords.error_msg') ];
                    echo json_encode($resp); die;
                }
            }else{
               $resp = $checkValidate; die;
            }
           echo json_encode($resp); die;
        }
    }


    public function updatePlaylistStatus(Request $request, $id){
        $checkValidate = validation($request->all(), ['status'=>'required'] );
        if($checkValidate['status'] == 1){
            $resp = change_status(['table'=>'admin_playlists', 'where'=>['id'=>$id],'data'=> ['status'=>$request->status]]);
            echo $resp;
        }else{
            echo json_encode($checkValidate);
        }
    }

    public function destroyPlaylist($id){
        $resp = singleDelete([ 'table'=>'admin_playlists','column'=>['id'], 'where'=>['id'=>$id], 'msg'=>__('frontWords.playlist').' '.__('adminWords.delete_success')] );
        echo $resp;   
    }

    public function bulkDeletePlaylist(Request $request){
        $checkValidate = validation($request->all(),['checked' =>'required'],__('adminWords.atleast').' '.__('frontWords.playlist').' '.__('adminWords.must_selected') );
        if($checkValidate['status'] == 1){
            $resp = bulkDeleteData(['table'=>'admin_playlists','column'=>['id'], 'msg'=>__('frontWords.playlist').' '.__('adminWords.delete_success'), 'request'=>$request->except('_token')]);
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }


    public function playlistGenres(){
        return view('adminplaylist::playlist_category.index');  
    }

    public function showPlaylistGenreData(){
        $playlist_genre = select(['table'=>'admin_playlist_genres','column'=>'*','order'=>['id','desc']]);
        return DataTables::of($playlist_genre)
        ->editColumn('checkbox',function($playlist_genre){
            return '<div class="checkbox danger-check"><input name="checked" id="checkboxAll'.$playlist_genre->id.'" type="checkbox" class=" CheckBoxes" value="'.$playlist_genre->id.'"><label for="checkboxAll'.$playlist_genre->id.'"></label></div>';
        })
        
        ->editColumn('created_at', function($playlist_genre){
            return Carbon::parse($playlist_genre->created_at)->diffForHumans(Carbon::now());
        })
        ->editColumn('status', function($playlist_genre){
            return '<div class="checkbox success-check><input id="switch'.$playlist_genre->id.'" name="status" class=" updateStatus" '.($playlist_genre->status == 1 ? 'checked':'').' type="checkbox" data-url="'.url('updatePlaylistGenre/'.$playlist_genre->id).'"><label class="custom-control-label" for="switch'.$playlist_genre->id.'"></label></div>';
        })
        ->addColumn('action', function ($playlist_genre){ 
            return '<div class="button-list"><a class="btn btn-sm btn-success mr-2 playlistGenrePopupToggle" data-url="'.url('getPlaylistGenreData/'.$playlist_genre->id).'" data-save="'.url('playlist_genres/'.$playlist_genre->id).'"><i class="far fa-edit"></i></a><button type="button" data-url="'.url('destroyPlaylistGenre/'.$playlist_genre->id).'" class="btn btn-sm btn-danger" id="deleteRecordById"><i class="fa fa-trash"></i></button></div>';
        })
        ->rawColumns(['checkbox','status','action'])->make(true);
    }

    public function getPlaylistGenreData($id){
        $playlistGenre = AdminPlaylistGenre::find($id);
        if(!empty($playlistGenre)){
            $resp = ['status'=>1, 'data'=>$playlistGenre];
        }else{
            $resp = ['status'=>0, 'msg'=>__('adminWords.error_msg')];
        }
        echo json_encode($resp);
    }

    public function updatePlaylistGenre(Request $request, $id){ 
        $checkValidate = validation($request->all(),['status' =>'required']);
        if($checkValidate['status'] == 1){
            $resp = change_status(['table'=>'admin_playlist_genres', 'column'=>'id', 'where'=>['id'=>$id],'data'=> ['status'=>$request->status]]);
            echo $resp;
        }else{
            echo json_encode($checkValidate);
        }
    }

    public function addEditPlaylistGenre(Request $request, $id){
        $rules = ['genre_name' => 'required'];

        $checkValidate = validation($request->except('_token'), $rules );
        if($checkValidate['status'] == 1){
            $arr = [
                'genre_name' => $request->genre_name,
                'genre_slug' => Str::slug($request->genre_name,'-'),
                'status' => isset($request->status) ? '1' : '0',
            ];
            $where = is_numeric($id) ? [['id','!=',$id], ['genre_slug','=', $arr['genre_slug']] ] : [['genre_slug','=', $arr['genre_slug']]];
            $playlistGenre = AdminPlaylistGenre::where($where)->get(); 
            if(count($playlistGenre) > 0){
                $resp = ['status'=>0, 'msg'=>__('adminWords.genre').' '.__('adminWords.already_exist')];
            }else{
                $genre = is_numeric($id) ? AdminPlaylistGenre::find($id) : [];
                
                if(!empty($genre)){
                    $genre->update($arr);
                    $msg = __('adminWords.genre').' '.__('adminWords.updated_msg');
                }else{
                    AdminPlaylistGenre::create($arr);
                    $msg = __('adminWords.genre').' '.__('adminWords.added_msg');
                }
                $resp = ['status'=>1, 'msg'=>$msg];
            }
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

    public function destroyPlaylistGenre($id){
        $resp = singleDelete([ 'table'=>'admin_playlist_genres','column'=>'id','where'=>['id'=>$id], 'msg'=>__('adminWords.genre').' '.__('adminWords.delete_success')]);
        echo $resp;
        
    }

    public function bulkDeletePlaylistGenre(Request $request){
        $checkValidate = validation($request->all(),['checked' =>'required'],__('adminWords.atleast').' '.__('adminWords.genre').' '.__('adminWords.must_selected'));
        if($checkValidate['status'] == 1){
            $resp = bulkDeleteData(['table'=>'admin_playlist_genres', 'column'=>'id', 'msg'=>__('adminWords.genre').' '.__('adminWords.delete_success'),'request'=>$request->except('_token')]);
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

    public function audio_player(){
        return view('audio::audio');
    }

    
}
