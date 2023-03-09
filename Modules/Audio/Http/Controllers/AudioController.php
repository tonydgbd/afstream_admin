<?php
namespace Modules\Audio\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use DataTables;
use Illuminate\Support\Carbon;
use Modules\Audio\Entities\AudioGenre;
use Modules\Artist\Entities\ArtistGenre;
use Modules\Setting\Entities\Settings;
use Modules\Audio\Entities\AudioArtist;
use Modules\Audio\Entities\Audio;
use Modules\Artist\Entities\Artist;
use Modules\Language\Entities\Language;
use Modules\AudioLanguage\Entities\AudioLanguage;
use Stevebauman\Purify\Facades\Purify;
use Modules\Setting\Entities\Currency;
use Str;
use Auth;
use Crypt;
use Illuminate\Support\Facades\Storage;
use BoyHagemann\Waveform\Waveform;
use App\User;

class AudioController extends Controller 
{
    public function index(){
        return view('audio::audio.index');
    }

    public function audioData(){
        $audios = select(['column' => ['audio.*', 'audio_genres.genre_name', 'audio_languages.language_name'], 'table' => 'audio', 'order'=>['id','desc'], 'join' => [['audio_genres','audio_genres.id','=','audio.audio_genre_id'],['audio_languages','audio_languages.id','=','audio.audio_language']] ]);
        $newArr = [];
        
        if(!empty($audios)){
            foreach($audios as $audio){
                $price = '';
                $role = 1;
                $artist_id = json_decode($audio->artist_id);
                $artist = Artist::select('artist_name')->where('id','=',$artist_id)->get();
                
                $dc = Settings::where('name', 'default_currency_id')->first();
                if(!empty($dc->value)){
                    $defaultCurrency = Currency::where('id',$dc->value)->first();
                }else{
                    $defaultCurrency = '';
                }          
                
                if(isset($audio->user_id) && !empty($audio->user_id)){
                    $user = User::select('role')->where('id','=',$audio->user_id)->first();
                    if(isset($user) && !empty($user)){
                        $role = $user->role;
                    }
                }
                
                if(isset($defaultCurrency) && isset($defaultCurrency->symbol) && !empty($defaultCurrency->symbol) && isset($audio->download_price) && !empty($audio->download_price)){
                    $price = $defaultCurrency->symbol.''.$audio->download_price;
                }
                
                array_push($newArr,['download_price'=>$price,'role'=>$role,'aws_upload'=> $audio->aws_upload,'id'=>$audio->id, 'audio_title'=>$audio->audio_title, 'audio_genre'=>$audio->genre_name, 'artist_name'=>(count($artist) > 0 ? $artist[0]['artist_name'] : '-'), 'language'=>$audio->language_name, 'created_at'=>$audio->created_at, 'status'=>$audio->status, 'image'=>$audio->image]);
            }
        }
        
        return DataTables::of($newArr)
            ->editColumn('checkbox',function($newArr){
                return '<div class="checkbox danger-check"><input name="checked" id="checkboxAll'.$newArr['id'].'" type="checkbox" class="CheckBoxes" value="'.$newArr['id'].'"><label for="checkboxAll'.$newArr['id'].'"></label></div>';
            })
            ->editColumn('image', function($newArr){
                if($newArr['image'] != '' && file_exists(public_path('/images/audio/thumb/'.$newArr['image'])))
                    $src = asset('public/images/audio/thumb/'.$newArr['image']);
                else
                    $src = asset('public/images/sites/500x500.png');
                return '<span class="img-thumb"><img src="'.$src.'" alt="" class="img-fluid" width="60px" height="60px"></span>';
            })
            ->editColumn('price', function($newArr){
                if(!empty($newArr['download_price'])){
                    return $newArr['download_price'];
                }else{
                    return __('adminWords.by_plan');
                }
            })
            ->editColumn('created_at', function($newArr){
                return date('d-m-Y', strtotime($newArr['created_at']));
            })
            ->editColumn('status', function($newArr){
                return '<div class="checkbox success-check"><input id="checkboxc'.$newArr['id'].'" name="status" class="updateStatus" '.($newArr['status'] == 1 ? 'checked':'').' type="checkbox" data-url="'.url('audio/status/'.$newArr['id']).'"><label class="custom-control-label" for="checkboxc'.$newArr['id'].'"></label></div>';
            })
            ->addColumn('action', function ($newArr){
                if(isset($newArr['role']) && !empty($newArr['role']) && $newArr['role'] == 1){
                    if($newArr['aws_upload'] == 1){
                        $download = '<a href="'.getSongAWSUrlHtml($newArr).'">
                            <i class="fa fa-download mr-2"></i> '.__("frontWords.download").'
                        </a> ';
                        
                    }else{    
                        $download = '<a href="javascript:void(0);" class="download_track" data-musicid="'. $newArr["id"] .'">
                            <i class="fa fa-download mr-2"></i> '.__("frontWords.download").'
                        </a>';
                    } 
                    $delete = '<a href="javascript:void(0); " data-url="'.url('audio/destroy/'.$newArr['id']).'" id="deleteRecordById"><i class="far fa-trash-alt mr-2 "></i>'.__('adminWords.delete').'</a>';
                }else{
                    $download = '';
                    $delete = '';
                }
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
                            <a href="'.url('audio/edit/'.$newArr['id']).'"><i class="far fa-edit mr-2"></i>'.__('adminWords.edit').'</a>
                        </li>
                        <li>
                            <a href="'.url('comments/audio/'.$newArr['audio_title'].'/'.Crypt::encrypt($newArr['id'])).'"><i class="fa fa-comment mr-2" aria-hidden="true"></i>'.__('adminWords.comment').'</a>
                        </li>
                        <li>
                            '.$delete.'
                        </li>
                        <li>
                            '.$download.'
                        </li>
                    </ul>
                </div>';
            })
            ->rawColumns(['checkbox','image','status','action'])->make(true);
    }

    public function create(){
        $data['artist'] = Artist::pluck('artist_name','id')->all();
        $data['audioGenre'] = AudioGenre::where('status',1)->pluck('genre_name','id')->all();
        $data['audioLanguage'] = AudioLanguage::where('status',1)->pluck('language_name','id')->all();
        $check_s3 = Settings::where('name', 'is_s3')->first();
        if(isset($check_s3) && !empty($check_s3)){
            $data['is_s3'] = $check_s3['value'];     
        }  
        return view('audio::audio.addEdit', $data);  
    }

    public function edit($id){
        $data['artist'] = Artist::pluck('artist_name','id')->all();
        $data['audioGenre'] = AudioGenre::pluck('genre_name','id')->all();
        $data['audioData'] = Audio::find($id);
        $data['audioLanguage'] = AudioLanguage::where('status',1)->pluck('language_name','id')->all();
        $check_s3 = Settings::where('name', 'is_s3')->first();
        if(isset($check_s3) && !empty($check_s3)){
            $data['is_s3'] = $check_s3['value'];     
        }
        if(!empty($data['audioData'])){
            return view('audio::audio.addEdit', $data);
        }else{
            return redirect()->back();
        }
    }

    public function addEditAudio(Request $request, $id){            
        
        $rules = [
            'audio_title' => 'required',
            'audio_genre_id' => 'required',
            'artist_id' => 'required',
            'audio_language' => 'required',
            'status' => 'required'
        ];
        if(!is_numeric($id)){
            $rules['image'] = 'required|mimes:jpg,jpeg,png|max:2048';
            $rules['audio'] = 'required|mimes:mp3,wav';
        }
        if(isset($request->download_price) && !empty($request->download_price)){
            $rules['download_price'] = 'numeric';            
        }
        $checkValidate = validation($request->except('_token'), $rules);
        if($checkValidate['status'] == 1){
            $slug = Str::slug($request->audio_title,'-');
            $where = is_numeric($id) ? [['id','!=',$id],['audio_slug','=',$slug]] : [['audio_slug','=',$slug]];

            $checkAudio = Audio::where($where)->first();
            if(!empty($checkAudio)){
                $resp = array('status'=>0, 'msg'=>__('adminWords.audio').' '.__('adminWords.already_exist'));
            }else{
                $audioCheck = is_numeric($id) ? Audio::find($id) : [];
                $data = $request->except('_token');
                $data['user_id'] = Auth::user()->id;
                $data['lyrics'] = $data['lyrics'];
                $data['description'] = Purify::clean($data['description']);
                $data['audio_slug'] = $slug;
                $data['artist_id'] = json_encode($request->artist_id);
                $data['status'] = isset($request->status) ? 1 : 0;
                $data['is_featured'] = isset($request->is_featured) ? 1 : 0;
                $data['is_trending'] = isset($request->is_trending) ? 1 : 0;
                $data['is_recommended'] = isset($request->is_recommended) ? 1 : 0;
                $data['aws_upload'] = isset($request->aws_upload) ? 1 : 0;
                $data['download_price'] = isset($request->download_price) ? $request->download_price : '';
                
                if(isset($request->release_date) && !empty($request->release_date)){
                    $releaseDate = date('Y-m-d', strtotime($request->release_date));                    
                    $data['release_date'] = $releaseDate;
                }                

                if($image = $request->file('image')){
                    $name = 'audio-'.time().'.webp';
                    $data['image'] = str_replace(' ','',$name);
                    upload_image($image, public_path().'/images/audio/thumb/', $name, '500x500');
                    if(!empty($audioCheck) && $audioCheck->image != ''){
                        delete_file_if_exist(public_path().'/images/audio/thumb/'.$audioCheck->image);
                    }
                }

                if($image = $request->file('banner_image')){
                    $name = 'audio-banner-'.time().'.webp';
                    $data['banner_image'] = str_replace(' ','',$name);
                    upload_image($image, public_path().'/images/audio/thumb/', $name, '860x839'); 
                    if(!empty($audioCheck) && $audioCheck->image != ''){
                        delete_file_if_exist(public_path().'/images/audio/thumb/'.$audioCheck->image);
                    }
                }

                if($audio = $request->file('audio')){

                    $durationMp3 = 0;
                    
                    if($data['aws_upload']){
                        $SrcDirectorty = env('AWS_DIRECTORY');
                        if(!empty($audioCheck) && $audioCheck->audio != ''){ 
                            delete_file_if_exist(public_path().'/images/audio'.$audioCheck->audio); 
                            if($exists = Storage::disk('s3')->exists($SrcDirectorty.'/'.$audioCheck->audio)){
                                Storage::disk('s3')->delete($SrcDirectorty.'/'.$audioCheck->audio);
                            }
                        }
                        $name = 'audio-'.time().rand(10,100) .'.'. $audio->getClientOriginalExtension();
                        $data['audio'] = str_replace(' ','',$name);
                        $audioName = Storage::disk('s3')->put($SrcDirectorty.'/'.$data['audio'], file_get_contents($audio), 'public');                        
                        if(isset($audioName)){
                            upload_audio(['audio'=>$audio, 'path'=>public_path().'/images/audio/aws/', 'filename'=>$data['audio']]);
                            $url = public_path().'/images/audio/aws/'.$data['audio'];
                            if(file_exists($url)){
                                $data['audio_duration'] = audio_duration([ 'path'=>$url]);
                                unlink($url);
                            }
                        }
                     
                    }else{
                        $audioName = 'audio-'.time().'.'.$audio->getClientOriginalExtension();
                        $data['audio'] = str_replace(' ','',$audioName);
                        upload_audio(['audio'=>$audio, 'path'=>public_path().'/images/audio', 'filename'=>$audioName]);
                        $data['audio_duration'] = audio_duration([ 'path' => public_path().'/images/audio/'.$data['audio'] ]);
                    }
                }
                
                $addAudio = !empty($audioCheck) ? $audioCheck->update($data) : Audio::create($data);
                if($addAudio){
                    foreach($request->artist_id as $artist){
                        $getAudioList = AudioArtist::where('artist_id',$artist)->get();
                        $idss = (!empty($audioCheck)) ? $id : $addAudio->id;  
                        if(sizeof($getAudioList) > 0){
                            $audioIds = json_decode($getAudioList[0]->audio_id);
                            if(!in_array($idss, $audioIds)){                   
                                array_push($audioIds, $idss);
                                $addupdatesong = AudioArtist::where('artist_id',$artist)->update(['audio_id'=>$audioIds]);
                            }
                        }else{
                            $dataArr = [
                                'artist_id' => $artist,
                                'audio_id' => (!empty($audioCheck)) ? json_encode([$id]) : json_encode([$addAudio->id])
                            ];
                            $addupdatesong = AudioArtist::create($dataArr);
                        }
                    }
                    $resp = array('status'=>1, 'msg'=>__('adminWords.audio').' '.__('adminWords.success_msg'));
                }else{
                    $resp = array('status'=>0, 'msg'=>__('adminWords.error_msg'));
                }
            }
        }else{
           $resp = $checkValidate;
        }
       echo json_encode($resp);
    }


    public function updateAudioStatus(Request $request, $id){
        $checkValidate = validation($request->all(), ['status'=>'required'] );
        if($checkValidate['status'] == 1){
            $resp = change_status(['table'=>'audio', 'where'=>['id'=>$id],'data'=> ['status'=>$request->status]]);
            echo $resp;
        }else{
            echo json_encode($checkValidate);
        }
    }

    public function destroyAudio($id){
        $resp = singleDelete([ 'table'=>'audio','column'=>['image','audio_title','audio','aws_upload'], 'where'=>['id'=>$id], 'msg'=>__('adminWords.audio').' '.__('adminWords.delete_success'), 'isImage'=>public_path().'/images/audio/','aws_upload'=>'1' ]);
        echo $resp;   
    }

    public function bulkDeleteAudio(Request $request){
        $checkValidate = validation($request->all(),['checked' =>'required'],__('adminWords.atleast').' '.__('adminWords.audio').' '.__('adminWords.must_selected') );
        if($checkValidate['status'] == 1){
            $resp = bulkDeleteData(['table'=>'audio','column'=>['id','image','audio','aws_upload'], 'msg'=>__('adminWords.audio').' '.__('adminWords.delete_success'), 'request'=>$request->except('_token'), 'isImage'=>public_path().'/images/audio/','aws_upload'=>'1']);
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }


    public function audioGenres(){
        return view('audio::audio_category.index');
    }

    public function showAudioGenreData(){
        $audio_genre = select(['table'=>'audio_genres','column'=>'*','order'=>['id','desc']]);
        return DataTables::of($audio_genre)
        ->editColumn('checkbox',function($audio_genre){
            return '<div class="checkbox danger-check"><input name="checked" id="checkboxAll'.$audio_genre->id.'" type="checkbox" class="CheckBoxes" value="'.$audio_genre->id.'"><label for="checkboxAll'.$audio_genre->id.'"></label></div>';
        })
        ->editColumn('image', function($audio_genre){
            if($audio_genre->image != '' && file_exists(public_path('/images/audio/audio_genre/'.$audio_genre->image)))
                $src = asset('public/images/audio/audio_genre/'.$audio_genre->image);
            else
                $src = asset('public/images/sites/500x500.png');
            return '<span class="img-thumb"><img src="'.$src.'" alt="" class="img-fluid" width="60px" height="60px"></span>';
        })
        ->editColumn('created_at', function($audio_genre){
            return date('d-m-Y', strtotime($audio_genre->created_at));
        })
        ->editColumn('status', function($audio_genre){
            return '<div class="checkbox success-check"><input id="checkboxc'.$audio_genre->id.'" name="status" class="updateStatus" '.($audio_genre->status == 1 ? 'checked':'').' type="checkbox" data-url="'.url('updateAudioGenre/'.$audio_genre->id).'"><label for="checkboxc'.$audio_genre->id.'"></label></div>';
        })
        ->addColumn('action', function ($audio_genre){
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
                            <a class="audioGenrePopupToggle" href="javascript:void(0)" data-url="'.url('getAudioGenreData/'.$audio_genre->id).'" data-save="'.url('audio_genres/'.$audio_genre->id).'"><i class="far fa-edit mr-2 "></i>'.__('adminWords.edit').'</a>
                        </li>
                        <li>
                            <a href="javascript:void(0); " data-url="'.url('destroyAudioGenre/'.$audio_genre->id).'" id="deleteRecordById"><i class="far fa-trash-alt mr-2 "></i>'.__('adminWords.delete').'</a>
                        </li>
                    </ul>
                </div>';
        })
        ->rawColumns(['checkbox','status','image','action'])->make(true);
    }

    public function getAudioGenreData($id){
        $genre = AudioGenre::find($id);
        if(!empty($genre)){
            $resp = ['status'=>1, 'data'=>$genre];
        }else{
            $resp = ['status'=>0, 'msg'=>__('adminWords.error_msg')];
        }
        echo json_encode($resp);
    }

    public function updateAudioGenre(Request $request, $id){ 
        $checkValidate = validation($request->all(),['status' =>'required']);
        if($checkValidate['status'] == 1){
            $resp = change_status(['table'=>'audio_genres', 'column'=>'id', 'where'=>['id'=>$id],'data'=> ['status'=>$request->status]]);
            echo $resp;
        }else{
            echo json_encode($checkValidate);
        }
    }

    public function addEditAudioGenre(Request $request, $id){
        $rules = ['genre_name' => 'required'];
        if(!is_numeric($id)){
            $rules['image'] = 'required|mimes:jpg,jpeg,png|max:2048';
        }
        $checkValidate = validation($request->except('_token'), $rules );
        if($checkValidate['status'] == 1){
            $arr = [
                'genre_name' => $request->genre_name,
                'genre_slug' => Str::slug($request->genre_name,'-'),
                'status' => isset($request->status) ? '1' : '0',
                'is_featured' => isset($request->is_featured) ? '1' : '0',
                'is_trending' => isset($request->is_trending) ? '1' : '0',
                'is_recommended' => isset($request->is_recommended) ? '1' : '0',
            ];
            $where = is_numeric($id) ? [['id','!=',$id], ['genre_slug','=', $arr['genre_slug']] ] : [['genre_slug','=', $arr['genre_slug']]];
            $audioGenre = AudioGenre::where($where)->get();
            if(count($audioGenre) > 0){
                $resp = ['status'=>0, 'msg'=>__('adminWords.genre').' '.__('adminWords.already_exist')];
            }else{
                $genre = is_numeric($id) ? AudioGenre::find($id) : [];
                if($image = $request->file('image')){
                    $name = 'audioGenre-'.time().'.webp';
                    $arr['image'] = str_replace(' ','',$name);
                    upload_image($image, public_path().'/images/audio/audio_genre/', $name, '500x500');
                    if(!empty($genre) && $genre->image != ''){
                        delete_file_if_exist(public_path().'/images/audio/audio_genre/'.$genre->image);
                    }
                }
                if(!empty($genre)){
                    $genre->update($arr);
                    $msg = __('adminWords.genre').' '.__('adminWords.updated_msg');
                }else{
                    AudioGenre::create($arr);
                    $msg = __('adminWords.genre').' '.__('adminWords.added_msg');
                }
                $resp = ['status'=>1, 'msg'=>$msg];
            }
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

    public function destroyAudioGenre($id){
        $resp = singleDelete([ 'table'=>'audio_genres','column'=>'id','where'=>['id'=>$id], 'msg'=>__('adminWords.genre').' '.__('adminWords.delete_success')]);
        echo $resp;
        
    }

    public function bulkDeleteAudioGenre(Request $request){
        $checkValidate = validation($request->all(),['checked' =>'required'],__('adminWords.atleast').' '.__('adminWords.genre').' '.__('adminWords.must_selected'));
        if($checkValidate['status'] == 1){
            $resp = bulkDeleteData(['table'=>'audio_genres', 'column'=>'id', 'msg'=>__('adminWords.genre').' '.__('adminWords.delete_success'),'request'=>$request->except('_token')]);
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

    public function audio_player(){
        return view('audio::audio');
    }
    
    public function getArtistRecordbylanguage(Request $request){
        
        $artists = [];
        $artists = Artist::select('id','artist_name')->whereJsonContains('audio_language_id', $request->getLanguage)->orderBy('id','desc')->get()->toArray();
        if(!empty($artists)){
             $resp = ['status'=>1, 'data'=>$artists];
        }else{
            $resp = ['status'=>0, 'data'=>$artists];
        }
        echo json_encode($resp);
    }
    
}
