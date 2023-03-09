<?php

namespace App\Http\Controllers\Artist;

use Modules\AudioLanguage\Entities\AudioLanguage;
use Modules\Artist\Entities\ArtistGenre;
use Modules\Audio\Entities\AudioArtist;
use Illuminate\Support\Facades\Storage;
use Stevebauman\Purify\Facades\Purify;
use Modules\Audio\Entities\AudioGenre;
use Modules\Setting\Entities\Currency;
use Modules\Setting\Entities\Settings;
use App\Http\Controllers\Controller;
use Modules\Artist\Entities\Artist;
use Modules\Audio\Entities\Audio;
use Illuminate\Http\Request; 
use wapmorgan\Mp3Info\Mp3Info;
use BoyHagemann\Waveform\Waveform;
use DataTables;
use Crypt;
use Auth;
use Str;

class AudioController extends Controller
{
    
    public function songsOfArtist($audios = null){
        $artistAudios = [];
        if(!empty($audios)){
            $artistId = Artist::where('user_id',Auth::user()->id)->first();
            if(!empty($artistId['id'])){
                foreach($audios as $audio){
                    $decodeIds = $audio->artist_id;  
                    if($decodeIds != 'null' && !empty($decodeIds)){
                        $dataId = json_decode($decodeIds);
                        if( in_array($artistId['id'], $dataId)) {
                            $artistAudios[] = $audio;    
                        }
                    }      
                }
            }
        }
        return $artistAudios;
    }

    public function index(){       
        return view('artist.audio.index');
    }

    public function audioData(){
        $audios = select(['column' => ['audio.*', 'audio_genres.genre_name', 'audio_languages.language_name'], 'table' => 'audio','where' => ['user_id'=> Auth::user()->id], 'order'=>['id','desc'], 'join' => [['audio_genres','audio_genres.id','=','audio.audio_genre_id'],['audio_languages','audio_languages.id','=','audio.audio_language']] ]);
        
        
        $newArr = [];         
        $artist_ids = [];
        
        foreach($audios as $audio){
            $artist_ids = json_decode($audio->artist_id);
            $artists = Artist::select('artist_name')->where('id','=',$artist_ids)->get();
            
           
            $dc = Settings::where('name', 'default_currency_id')->first();
            if(!empty($dc->value)){
                $defaultCurrency = Currency::where('id',$dc->value)->first();
            }else{
                $defaultCurrency = '';
            }          

            if(!empty($defaultCurrency) && isset($defaultCurrency->symbol) && !empty($defaultCurrency->symbol && !empty($audio->download_price))){
                $price = $defaultCurrency->symbol.''.$audio->download_price;
            }elseif(isset($audio->download_price) && !empty($audio->download_price)){
                $price = $audio->download_price;
            }else{
                $price = '';
            }
            array_push($newArr,['aws_upload' => $audio->aws_upload, 'id'=>$audio->id, 'audio_title'=>$audio->audio_title, 'audio_genre'=>$audio->genre_name, 'artist_name'=>(count($artists) > 0 ? $artists[0]['artist_name'] : '-'), 'language'=>$audio->language_name, 'created_at'=>$audio->created_at, 'status'=>$audio->status, 'image'=>$audio->image, 'download_price'=>$price]);
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
                $price = '';
                if(!empty($newArr['download_price'])){
                    $price = $newArr['download_price'];
                }else{
                    $price = __('adminWords.by_plan');
                }
                return $price;
            })
            ->editColumn('created_at', function($newArr){
                return date('d-m-Y', strtotime($newArr['created_at']));
            })
            ->editColumn('status', function($newArr){
                return '<div class="checkbox success-check"><input id="checkboxc'.$newArr['id'].'" name="status" class="updateStatus" '.($newArr['status'] == 1 ? 'checked':'').' type="checkbox" data-url="'.url('artist/audio/status/'.$newArr['id']).'"><label class="custom-control-label" for="checkboxc'.$newArr['id'].'"></label></div>';
            })
            ->addColumn('action', function ($newArr){
                if($newArr['aws_upload'] == 1){
                    $download = '<a href="'.getSongAWSUrlHtml($newArr).'">
                        <i class="fa fa-download mr-2"></i> '.__("frontWords.download").'
                    </a> ';
                    
                }else{    
                    $download = '<a href="javascript:void(0);" class="download_track" data-musicid="'. $newArr["id"] .'">
                        <i class="fa fa-download mr-2"></i> '.__("frontWords.download").'
                    </a>';
                }
                return '<a class="action-btn" href="javascript:void(0);">
                    <svg class="default-size" viewBox="0 0 341.333 341.333">
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
                <div class="action-option">
                    <ul>
                        <li>
                            <a href="'.route('artist.audio_edit',['id'=>$newArr['id']]).'"><i class="far fa-edit mr-2"></i>'.__('adminWords.edit').'</a>
                        </li>                        
                        <li>
                            <a href="javascript:void(0); " data-url="'.url('artist/audio/destroy/'.$newArr['id']).'" id="deleteRecordById"><i class="far fa-trash-alt mr-2 "></i>'.__('adminWords.delete').'</a>
                        </li>
                        <li>
                            '.$download.'
                        </li>
                    </ul>
                </div>';
            })
            ->rawColumns(['checkbox','image','status','action'])->make(true);
    }
 
    public function audioCreate(){
        $data['artist'] = Artist::orderBy('id','desc')->where('status',1)->pluck('artist_name','id')->all();
        $data['audioGenre'] = AudioGenre::where('status',1)->pluck('genre_name','id')->all(); 
        return view('artist.audio.addEdit',$data);
    }

    public function audioEdit(Request $request, $id){
        $data['artist'] = Artist::orderBy('id','desc')->where('status',1)->pluck('artist_name','id')->all();
        $data['audioGenre'] = AudioGenre::where('status',1)->pluck('genre_name','id')->all();
        $data['audioData'] = Audio::find($id);
        $data['audioLanguage'] = AudioLanguage::where('status',1)->pluck('language_name','id')->all();
        $check_s3 = Settings::where('name', 'is_s3')->first();
        if(isset($check_s3) && !empty($check_s3)){
            $data['is_s3'] = $check_s3['value'];     
        }
        if(!empty($data['audioData'])){
            return view('artist.audio.addEdit', $data);
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
            'status' => 'required',
            'is_price' => 'required',
        ];
        if(!is_numeric($id)){
            $rules['image'] = 'required|mimes:jpg,jpeg,png|max:2048';
            $rules['audio'] = 'required|mimes:mp3,wav';
        }
        
        if(isset($request->is_price) && !empty($request->is_price) && $request->is_price == 1){
            $rules['download_price'] = 'required|numeric';            
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
                if(isset($request->is_price) && $request->is_price != ''){
                    if($request->is_price == '0'){
                        $data['is_price'] = '0';
                        $data['download_price'] = '';
                    }else{
                        $data['is_price'] = '1';
                        $data['download_price'] = isset($request->download_price) ? $request->download_price : '';        
                    }
                }

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
