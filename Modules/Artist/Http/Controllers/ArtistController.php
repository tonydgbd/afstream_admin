<?php

namespace Modules\Artist\Http\Controllers;

use Modules\AudioLanguage\Entities\AudioLanguage; 
use Illuminate\Contracts\Support\Renderable;
use Modules\Artist\Entities\ArtistGenre;
use Modules\Setting\Entities\Currency;
use Modules\Artist\Entities\Artist;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use App\AdminAudioPayment;
use App\ArtistAudioPayment;
use DataTables;
use App\User;
use Str;
use Hash;

class ArtistController extends Controller 
{
    public function index(){
        return view('artist::artist.index');
    }

    public function artistData(Request $request){

        if(isset($request->from_date) && !empty($request->from_date) &&  $request->from_date != "Invalid date" && isset($request->to_date) && !empty($request->to_date)){

            if(isset($request->artist_status) && !empty($request->artist_status)){

                $artists = select(['column' => ['artists.id','artists.image','artists.audio_language_id','artists.artist_name','artists.dob', 'artist_genres.genre_name', 'users.artist_verify_status','artists.status','artists.user_id', 'artists.created_at'], 'table' => 'artists', 'order'=>['id','desc'], 'join' => [['artist_genres','artists.artist_genre_id','=','artist_genres.id'],['users','artists.user_id','=','users.id']] ])->whereBetween('created_at', [$request->from_date, $request->to_date])->where('artist_verify_status','=',$request->artist_status);
                
            }else{
                $artists = select(['column' => ['artists.id','artists.image','artists.audio_language_id','artists.artist_name','artists.dob', 'artist_genres.genre_name', 'users.artist_verify_status','artists.status','artists.user_id', 'artists.created_at'], 'table' => 'artists', 'order'=>['id','desc'], 'join' => [['artist_genres','artists.artist_genre_id','=','artist_genres.id'],['users','artists.user_id','=','users.id']] ])->whereBetween('created_at', [$request->from_date, $request->to_date]);
            }
        }else{
            $artists = select(['column' => ['artists.id','artists.image','artists.audio_language_id','artists.artist_name','artists.dob', 'artist_genres.genre_name', 'users.artist_verify_status','artists.status','artists.user_id', 'artists.created_at'], 'table' => 'artists', 'order'=>['id','desc'], 'join' => [['artist_genres','artists.artist_genre_id','=','artist_genres.id'],['users','artists.user_id','=','users.id']] ]);
        }
        
            
        return DataTables::of($artists)
            ->editColumn('checkbox',function($artists){
                return '<div class="checkbox danger-check"><input name="checked" id="checkboxAll'.$artists->id.'" type="checkbox" class="CheckBoxes" value="'.$artists->id.'"><label for="checkboxAll'.$artists->id.'" class="custom-control-label"></label></div>';
            }) 
            ->editColumn('image', function($artists){
                if($artists->image != '' && file_exists(public_path('/images/artist/'.$artists->image)))
                    $src = asset('public/images/artist/'.$artists->image);
                else
                    $src = asset('public/images/sites/500x500.png');
                return '<span class="img-thumb"><img src="'.$src.'" alt="" class="img-fluid" width="60px" height="60px"></span>';
            })
            
            ->editColumn('balance', function($artists){ 
                if(!empty($artists->artist_verify_status) && !empty($artists->user_id)){ 
                    $checkSalesAmount = AdminAudioPayment::where(['artist_id'=>$artists->user_id,'status'=>'1'])->sum('amount');
                    $currSymbol = Currency::select('symbol')->where('active', 1)->first();
                    $withdrawAmount = ArtistAudioPayment::where(['artist_id'=>$artists->user_id,'status'=>'1'])->sum('amount');
                    if(isset($currSymbol) && !empty($currSymbol->symbol)){
                        $balance = $currSymbol->symbol.($checkSalesAmount - $withdrawAmount);
                    }else{
                        $balance = $checkSalesAmount - $withdrawAmount;
                    }
                }else{
                    $balance = '';
                }
                return $balance; })
                
            ->editColumn('verify_status', function($artists){ 
                if(!empty($artists->artist_verify_status) && !empty($artists->user_id)){ 
                    $pending = '';
                    $approve = '';
                    $reject = '';
                    if($artists->artist_verify_status == "P"){
                        $pending = 'selected';
                    }elseif($artists->artist_verify_status == "R"){
                        $reject = 'selected';
                    }elseif($artists->artist_verify_status == "A"){
                        $approve = 'selected';
                    }
                    $verifyStatus = '<div class="artistVerisySelectBox"> 
                                <select class="form-control btn-square changeArtistVerifyStatus" name="varify_status" style="width: 58%;" data-url="'.url('artist/varify_status/'.$artists->user_id).'">                 
                                    <option value="P"'. $pending .'>'.__('adminWords.pending').'</option>  
                                    <option value="A"'. $approve .'>'. __('adminWords.approved').'</option> 
                                    <option value="R"'. $reject .'>'. __('adminWords.reject').'</option>
                                </select> 
                            </div>';
                }else{
                    $verifyStatus = '';
                }
                return $verifyStatus; })

            ->editColumn('created_at', function($artists){
                return date('d-m-Y', strtotime($artists->created_at));
            })
            ->editColumn('status', function($artists){
                return '<div class="checkbox success-check"><input id="checkboxc'.$artists->id.'" name="status" class="updateStatus" '.($artists->status == 1 ? 'checked':'').' type="checkbox" data-url="'.url('artist/status/'.$artists->id).'"><label for="checkboxc'.$artists->id.'"></label></div>';
            })
            ->addColumn('action', function ($artists) {
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
                            <a href="'.url('artist/edit/'.$artists->id).'"><i class="far fa-edit mr-2 "></i>'.__('adminWords.edit').'</a>
                        </li>
                        <li>
                            <a href="javascript:void(0); " data-url="'.url('artist/destroy/'.$artists->id).'" id="deleteRecordById"><i class="far fa-trash-alt mr-2 "></i>'.__('adminWords.delete').'</a>
                        </li>
                    </ul>
                </div>';
            })
            ->rawColumns(['checkbox','artist_name','image','verify_status','balance','status','action'])->make(true);
    }

    public function addEditArtist(Request $request, $id){
        $rules = [ 
            'artist_name' => 'required',
            'artist_genre' => 'required',
            'audio_language_id' => 'required',
            'artist_verify_status' => 'required',
            'email' => 'required|email|max:255'   
        ];
        if(!is_numeric($id)){
            $rules['image'] = 'required|mimes:jpg,jpeg,png|max:2048';
        }
        $checkValidate = validation($request->except('_token'), $rules);
        if($checkValidate['status'] == 1){
            $slug = Str::slug($request->artist_name,'-');
            $where = is_numeric($id) ? [['id','!=',$id],['artist_name','=',$slug]] : [['artist_name','=',$slug]];
            $checkArtist = Artist::where($where)->first();
            if(!empty($checkArtist)){
                $resp = array('status'=>0, 'msg'=>__('adminWords.artist').' '.__('adminWords.already_exist'));
            }else{
                $checkArtist = is_numeric($id) ? Artist::find($id) : [];

                $user = [];
                $data = [];                
                
                $user['name'] = $request->artist_name;                    
                if($image = $request->file('image')){
                    $name = 'user'.'-'.time().'.'.$image->getClientOriginalExtension();
                    $user['image'] = str_replace(' ','',$name);
                    upload_image($image, public_path().'/images/user/', $user['image']);                    
                }                     

                if(isset($request->email) && !empty($request->email)){
                    $user['email'] = $request->email;
                }
                if(isset($request->password) && !empty($request->password)){
                    $user['password'] = Hash::make($request->password);
                }
                if(isset($request->artist_verify_status) && !empty($request->artist_verify_status)){
                    $user['artist_verify_status'] = $request->artist_verify_status;
                }
                $user['role'] = 2;                        

                if(isset($checkArtist->user_id) && !empty($checkArtist->user_id)){
                    $checkUser = User::find($checkArtist->user_id);
                    $checkMail = select(['table'=>'users', 'column'=>'id', 'where'=>[['email',$request->email],['id','!=', $checkArtist->user_id]], 'single'=>1]);
                    if(!empty($checkMail)){
                        echo json_encode(array('status'=>0, 'msg'=>__('adminWords.email').' '.__('adminWords.already_exist') ));
                        return;
                    }
                    if(!empty($checkUser) && $checkUser->image != ''){
                        delete_file_if_exist(public_path().'/images/user/'.$checkUser->image);
                    }
                    $updateUser = $checkUser->update($user);  
                }else{
                    $checkMail = select(['table'=>'users', 'column'=>'id', 'where'=>[['email',$request->email]], 'single'=>1]);
                    if(!empty($checkMail)){
                        echo json_encode(array('status'=>0, 'msg'=>__('adminWords.email').' '.__('adminWords.already_exist') ));
                        return;
                    }
                    $user['email'] = $request->email;
                    $addUser = User::create($user);
                }

                if(isset($addUser->id) && !empty($addUser->id)){ 
                    $data['user_id'] = $addUser->id;
                }


                //$data = $request->except('_token');
                $data['artist_name'] = $request->artist_name;
                $data['audio_language_id'] = json_encode($request->audio_language_id);
                $data['artist_genre_id'] = $request->artist_genre;
                $data['artist_slug'] = $slug;
                $data['status'] = isset($request->status) ? 1 : 0; 
                $data['is_featured'] = isset($request->is_featured) ? 1 : 0;
                $data['is_trending'] = isset($request->is_trending) ? 1 : 0;
                $data['is_recommended'] = isset($request->is_recommended) ? 1 : 0;
                unset($data['artist_genre']);
                if($image = $request->file('image')){
                    $name = 'artist-'.time().'.webp';
                    $data['image'] = str_replace(' ','',$name);
                    upload_image($image, public_path().'/images/artist/', $name, '500x500');
                    if(!empty($checkArtist) && $checkArtist->image != ''){
                        delete_file_if_exist(public_path().'/images/artist/'.$checkArtist->image);
                    }
                }

                $addArtist = empty($checkArtist) ? Artist::create($data) : $checkArtist->update($data);
                $resp = ($addArtist) ? ['status'=>1, 'msg'=>__('adminWords.artist').' '.__('adminWords.success_msg')] : ['status'=>0, 'msg'=>__('adminWords.delete_success')];
            }
        }else{
           $resp = $checkValidate;
        }
        echo json_encode($resp);
    }
    
    public function createArtist(){
        $data['artistGenre'] = ArtistGenre::pluck('genre_name','id')->all();
        return view('artist::artist.addEdit', $data);
    }

    public function editArtist($id){
        $artistData = select(['column' => ['artists.id','artists.image','artists.audio_language_id','artists.artist_name','artists.dob', 'artist_genres.genre_name', 'users.artist_verify_status','artists.status','artists.description','artists.is_featured','artists.is_trending','artists.is_recommended','artists.artist_genre_id','artists.listening_count','artists.user_id','users.email','users.password', 'artists.created_at'], 'where'=>[['artists.id',$id]],'table' => 'artists', 'order'=>['id','desc'], 'join' => [['artist_genres','artists.artist_genre_id','=','artist_genres.id'],['users','artists.user_id','=','users.id']] ]);
        $data['artistData'] = !empty($artistData) ? $artistData[0] : [];
        $data['artistGenre'] = ArtistGenre::pluck('genre_name','id')->all();
        return view('artist::artist.addEdit', $data);
    }

    function updateArtistStatus(Request $request, $id){
        $checkValidate = validation($request->all(),['status' =>'required']);
        if($checkValidate['status'] == 1){
            $artistDetail = Artist::find($id);
            if(!empty($artistDetail->user_id)){
                $userDetail = User::find($artistDetail->user_id);
                if(!empty($userDetail)){
                    change_status(['table'=>'users', 'column'=>'id', 'where'=>['id'=>$userDetail->id],'data'=> ['status'=>$request->status]]);
                }
            }
            $resp = change_status(['table'=>'artists', 'column'=>'id', 'where'=>['id'=>$id],'data'=> ['status'=>$request->status]]);
            echo $resp;
        }else{
            echo json_encode($checkValidate);
        }
    }

    function updateArtistVerifyStatus(Request $request, $id){
        $checkValidate = validation($request->all(),['varify_status' =>'required']);
        if($checkValidate['status'] == 1){
            $resp = change_status(['table'=>'users', 'column'=>'id', 'where'=>['id'=>$id],'data'=> ['artist_verify_status'=>$request->varify_status]]);
            echo $resp;
        }else{
            echo json_encode($checkValidate);
        }
    }
    
    public function destroyArtist($id){
        $resp = singleDelete([ 'table'=>'artists','column'=>['image','artist_name'], 'where'=>['id'=>$id], 'msg'=> __('adminWords.artist').' '.__('adminWords.delete_success'), 'isImage'=>public_path().'/images/artist/' ]);
        echo $resp;        
    }

    function bulkDeleteArtistData(Request $request){
        $checkValidate = validation($request->all(),['checked' =>'required'], __('adminWords.atleast').' '.__('adminWords.artist').' '.__('adminWords.must_selected') );
        if($checkValidate['status'] == 1){
            $resp = bulkDeleteData(['table'=>'artists','column'=>'id', 'msg'=>__('adminWords.artist').' '.__('adminWords.delete_success'), 'request'=>$request->except('_token')]);
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

    public function artistGenres(){
        return view('artist::artist_genre.index');
    } 

    public function artistGenreData(){
        $artist_genre = select(['table'=>'artist_genres','column'=>'*','order'=>['id','desc']]);
        return DataTables::of($artist_genre)
        ->editColumn('checkbox',function($artist_genre){
            return '<div class="checkbox danger-check"><input name="checked" id="checkboxAll'.$artist_genre->id.'" type="checkbox" class="CheckBoxes" value="'.$artist_genre->id.'"><label for="checkboxAll'.$artist_genre->id.'" class="custom-control-label"></label></div>';
        })
        ->editColumn('created_at', function($artist_genre){
            return date('d-m-Y', strtotime($artist_genre->created_at));
        })
        ->addColumn('action', function ($artist_genre){ 
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
                            <a class="artistGenrePopupToggle" href="javascript:void(0)" data-url="'.url('genre/data/'.$artist_genre->id).'" data-save="'.url('artist/genre/addEdit/'.$artist_genre->id).'"><i class="far fa-edit mr-2 "></i>'.__('adminWords.edit').'</a>
                        </li>
                        <li>
                            <a href="javascript:void(0); " data-url="'.url('artist/genre/destroy/'.$artist_genre->id).'" id="deleteRecordById"><i class="far fa-trash-alt mr-2 "></i>'.__('adminWords.delete').'</a>
                        </li>
                    </ul>
                </div>';
        })
        ->rawColumns(['checkbox','status','action'])->make(true);
    }

    public function getArtistGenreData($id){
        $genre = ArtistGenre::find($id);
        if(!empty($genre)){
            $resp = ['status'=>1, 'data'=>$genre];
        }else{
            $resp = ['status'=>0, 'msg'=>__('adminWords.error_msg')];
        }
        echo json_encode($resp);
    }

    public function updateArtistGenre(Request $request, $id){ 
        $checkValidate = validation($request->all(),['status' =>'required']);
        if($checkValidate['status'] == 1){
            $resp = change_status(['table'=>'artist_genres', 'column'=>'id', 'where'=>['id'=>$id],'data'=> ['status'=>$request->status]]);
            echo $resp;
        }else{
            echo json_encode($checkValidate);
        }
    }

    public function addEditArtistGenre(Request $request, $id){
        $checkValidate = validation($request->except('_token'), ['genre_name' => 'required'] );
        if($checkValidate['status'] == 1){
            $arr = [
                'genre_name' => $request->genre_name,
                'genre_slug' => Str::slug($request->genre_name,'-'),
            ];
            $where = is_numeric($id) ? [['id','!=',$id], ['genre_slug','=', $arr['genre_slug']] ] : [['genre_slug','=', $arr['genre_slug']]];
            $artistGenre = ArtistGenre::where($where)->get();
            if(count($artistGenre) > 0){
                $resp = ['status'=>0, 'msg'=> __('adminWords.genre').' '.__('adminWords.already_exist')];
            }else{
                $genre = is_numeric($id) ? ArtistGenre::find($id) : [];
                if(!empty($genre)){
                    $genre->update($arr);
                    $msg = __('adminWords.genre').' '.__('adminWords.updated_msg');
                }else{
                    ArtistGenre::create($arr);
                    $msg = __('adminWords.genre').' '.__('adminWords.added_msg');
                }
                $resp = ['status'=>1, 'msg'=>$msg];
            }
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

    public function destroyArtistGenre($id){
        $resp = singleDelete([ 'table'=>'artist_genres','column'=>'id','where'=>['id'=>$id], 'msg'=>__('adminWords.genre').' '.__('adminWords.delete_success') ]);
        echo $resp;
    }

    public function bulkDeleteArtistGenre(Request $request){
        $checkValidate = validation($request->all(),['checked' =>'required'],__('adminWords.atleast').' '.__('adminWords.genre').' '.__('adminWords.must_selected') );
        if($checkValidate['status'] == 1){
            $resp = bulkDeleteData(['table'=>'artist_genres', 'column'=>'id', 'msg'=>__('adminWords.genre').' '.__('adminWords.delete_success'),'request'=>$request->except('_token')]);
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }
    
    
}
