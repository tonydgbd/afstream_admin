<?php

namespace App\Http\Controllers;

use Modules\Audio\Entities\Audio;
use Modules\Album\Entities\Album;
use Modules\Artist\Entities\Artist;
use Modules\Audio\Entities\AudioArtist;
use Modules\Audio\Entities\AudioGenre;
use Modules\Radio\Entities\Radio;
use Modules\Setting\Entities\Currency;
use Modules\Plan\Entities\Plan;
use Modules\Setting\Entities\PaymentMethod;
use Modules\AdminPlaylist\Entities\AdminPlaylist;
use Modules\Setting\Entities\Notification;
use Modules\General\Entities\Testimonial;
use Modules\General\Entities\BlogCategories;
use Modules\Language\Entities\Language;
use Illuminate\Support\Facades\Storage;
use Modules\Setting\Entities\Settings;
use Modules\General\Entities\Slider;
use Modules\General\Entities\Blogs;
use Modules\General\Entities\Pages;
use Modules\General\Entities\Faq;
use App\ArtistIntegrationSetting;
use App\Helpers\currencyRate;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\UserAction;
use Carbon\Carbon;
use App\Playlist;
use App\TopDetail;
use App\Comment;
use Newsletter;
use App\Favourite;
use DB;
use Auth;
use Hash;
use App\User;
use App\UserHistory;
use Crypt;
use Session;
use Cookie;
use Stevebauman\Purify\Facades\Purify;
use ZipArchive;
use File;
use Alaouy\Youtube\Facades\Youtube;
use stdClass;


class HomeController extends Controller{
    

    public function getUserLang(){
        if(isset(Auth::user()->id)){
            $language = Favourite::where('user_id', Auth::user()->id)->get();
            $setLanguage = [];
            if(sizeof($language) > 0){
                $setLanguage = json_decode($language[0]->user_language);
            }
        }else{
            $setLanguage = \Cookie::get('lang_filter');
        }
        return $setLanguage;
    }

    public function home_top_tracks(){

        $data = [];        
        $trending_from = Carbon::now()->subDays(30)->toDateString();
        $release_from = Carbon::now()->subDays(15)->toDateString();   
        $toDate = Carbon::now()->toDateString();
        $setLang = $this->getUserLang();
        if($setLang != '' && sizeof($setLang) > 0){
            $data['playlistData'] = AdminPlaylist::where('status','1')->whereIn('audio_language', $setLang)->orderBy('id','desc')->get(); 
            $data['today_top'] = Audio::whereIn('audio_language', $setLang)->where('listening_count' , '>','5')->whereDate('updated_at', '=', date('Y-m-d'))->orderBy('listening_count', 'desc')->where('status','1')->get();       
            $data['trending_song'] = Audio::whereIn('audio_language', $setLang)->where('listening_count' , '>=','2')->whereBetween('created_at',[$trending_from,$toDate])->orderBy('listening_count', 'desc')->where('status','1')->get();
            $data['new_release'] = Audio::whereIn('audio_language', $setLang)->whereBetween('release_date',[$release_from,$toDate])->where('status','1')->get();
        }else{
            $data['playlistData'] = AdminPlaylist::where('status','1')->orderBy('id','desc')->get()->toArray(); 
            $data['today_top'] = Audio::where('listening_count' , '>','5')->whereDate('updated_at', '=', date('Y-m-d'))->orderBy('listening_count', 'desc')->where('status','1')->get();       
            $data['trending_song'] = Audio::where('listening_count' , '>=','2')->whereBetween('created_at',[$trending_from,$toDate])->orderBy('listening_count', 'desc')->where('status','1')->get();
            $data['new_release'] = Audio::whereBetween('release_date',[$release_from,$toDate])->where('status','1')->get();
        }
        return $data;
    }    


    public function index(Request $request){ 
        
        $data = [];
        $data = $this->home_top_tracks();                   
        $data['admin_playlist'] = $this->getAdminAddedPlaylist($data['playlistData']);        

        $is_dashboard = Settings::where('name', 'is_dashboard')->first(); 

        if($request->ajax()){     
            if(isset($is_dashboard) && !empty($is_dashboard->value) && $is_dashboard->value != 'dashboard') {               
                return redirect()->route('home2') ;
            }else{
                $html = view('ajax.dashboard',$data)->render();
                $response['type'] = 'home';                 
            }
            $response['status'] = true;
            $response['html'] = $html;
            return response()->json($response);
        }else{
            if(isset($is_dashboard) && !empty($is_dashboard->value) && $is_dashboard->value != 'dashboard') {
                return redirect()->route('home2') ;
            }else{
                return view('front.dashboard', $data)->render();
            }           
        }
    }
    
    public function home2(Request $request){ 
        
        $data = [];
        $ytPlaylists = [];
        $data = $this->home_top_tracks();
        $data['top_album'] = TopDetail::select('top_album')->limit(6)->get();        
        $data['top_audio'] = TopDetail::select('top_audio')->limit(6)->get();  
        $data['genres'] = AudioGenre::where('status',1)->limit(6)->get();  
        $data['top_artist'] = TopDetail::select('top_artist')->limit(6)->get();        

        $is_youtube = Settings::where('name', 'is_youtube')->first();       

        if(!empty($is_youtube) && $is_youtube['value'] == 1 && !empty(env('YOUTUBE_CHANNEL_KEY'))) {           

            $ytPlaylists = Youtube::getPlaylistsByChannelId(env('YOUTUBE_CHANNEL_KEY'));

            if(!empty(env('YT_COUNTRY_CODE'))){
                $data['popularYtVideos'] = Youtube::getPopularVideos(env('YT_COUNTRY_CODE'));    
            }else{
                $data['popularYtVideos'] = []; 
            }

            $data['is_youtube'] = 1;

        }else{
            $data['is_youtube'] = 0;
        }

        $data['ytPlaylists'] = $ytPlaylists;

        if($request->ajax()){     
            $html = view('ajax.dashboard_2',$data)->render();
            $response['status'] = true;
            $response['html'] = $html;
            $response['type'] = 'home_2';  
            return response()->json($response);
        }else{
            return view('front.dashboard_2',$data)->render();
        }
    }


    public function get_song_list(Request $request){
        $input = $request->except('_token');
        $checkValidate = validation($input, [ 'musiclist'=>'required' ]);
        if($checkValidate['status'] == 1){
            $setLang = $this->getUserLang();
            if($setLang != '' && sizeof($setLang) > 0){
                $getAudio = Audio::whereIn('audio_language', $setLang)->where('status', 1)->inRandomOrder()->limit(1)->get();               
            }else{
                $getAudio = Audio::inRandomOrder()->limit(1)->get();
            }
            if(count($getAudio) > 0){
                $artist_name = $this->getArtistName(['artist_id' => $getAudio[0]->artist_id]);
                $resp = [
                    'status' => 'default',
                    'image' => url('/public/images/audio/thumb/'.$getAudio[0]->image),
                    'mid' => $getAudio[0]->id,
                    'share_uri' => url('audio/single/'.$getAudio[0]->id.'/'.$getAudio[0]->audio_slug),
                    'song_name' =>  $getAudio[0]->audio_title,
                    'mp3url' => ($getAudio[0]->aws_upload == 1) ? $this->getSongAWSUrl($getAudio) : url('/public/images/audio/'.$getAudio[0]->audio),
                    'is_aws' => $getAudio[0]->aws_upload,
                    'artists' => $artist_name
                ];
            }else{
                $resp = ['status' => 0, 'msg' => __('frontWords.no_song_err') ];
            }
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

    public function play_single_music(Request $request){
        
        $checkValidate = validation($request->except('_token'), [
            'musicid' => 'required',
            'musictype' => 'required'
        ]);
        
        if($checkValidate['status'] == 1){
            $banner_image = '';
            if($request->musictype == 'artist'){
                $artist = AudioArtist::where('artist_id',$request->musicid)->get();
                $getArtistDetail = Artist::where('id', $request->musicid)->get();
                if(sizeof($artist) > 0){
                    $songArr = (object)[];
                    $audioId = json_decode($artist[0]->audio_id);  
                    if(!empty($getArtistDetail)){
                        $artistCountUpdate = Artist::where('id', $request->musicid)->update(['listening_count' => DB::raw('listening_count + 1')]);
                    }
                    foreach($audioId as $ID){
                        $audios = Audio::where('id', $ID)->get();
                        foreach($audios as $audio){
                            if(isset(Auth::user()->id)){
                                $checkHistory = UserHistory::where('user_id', Auth::user()->id)->get();
                                if(sizeof($checkHistory) > 0){
                                    $audioId = json_decode($checkHistory[0]->audio_id);
                                    if(!in_array($audio->id, $audioId)){
                                        array_push($audioId, $audio->id);
                                    }
                                    UserHistory::where('user_id', Auth::user()->id)->update([ 'audio_id' => json_encode($audioId) ]);
                                }else{
                                    UserHistory::create(['user_id' => Auth::user()->id, 'audio_id' => json_encode([$audio->id]) ]);
                                }
                            }
                            if(!empty($audio->banner_image)){
                                $banner_image = url('public/images/audio/thumb/'.$audio->banner_image);
                            }else{
                                $banner_image = url('public/images/index_bg.png');
                            }
                            $songArr = (object)[
                                'mid' => $audio->id,
                                'mp3url' => ($audio->aws_upload == 1) ? $this->getSongAWSUrl($audios) : url('/public/images/audio/'.$audio->audio),
                                'song_name' => $audio->audio_title,
                                'artists' => (!empty($getArtistDetail)) ? $getArtistDetail[0]->artist_name : 'Unknown',
                                'image' => url('/public/images/artist/'.$getArtistDetail[0]->image),
                                'banner_image' => $banner_image,
                                'share_uri' => url('audio/single/'.$audio->id.'/'.$audio->audio_slug),
                                'is_aws' => $audio->aws_upload,
                                'status' => 'success'
                            ];
                        }   
                        $resp[] = $songArr;
                    }
                }else{
                    $resp = ['status' => 'false', 'msg' => __('frontWords.no_artist_song_err')];
                }
            }
            if($request->musictype == 'album'){ 
                $album = Album::where('id',$request->musicid)->select('song_list','image')->get();
                if(sizeof($album) > 0){
                    $countUpdate = Album::where('id',$request->musicid)->update(['listening_count' => DB::raw('listening_count + 1')]);
                    $song = json_decode($album[0]['song_list']);
                    for($i=0; $i<sizeof($song); $i++){
                        $songDetail = Audio::where('id', $song[$i])->get();
                        $songArr = (object)[];
                        if(sizeof($songDetail) > 0){
                            foreach($songDetail as $detail){
                                
                                if(isset(Auth::user()->id)){
                                    $checkHistory = UserHistory::where('user_id', Auth::user()->id)->get();
                                    if(sizeof($checkHistory) > 0){
                                        $audioId = json_decode($checkHistory[0]->audio_id);
                                        if(!in_array($detail->id, $audioId)){
                                            array_push($audioId, $detail->id);
                                        }
                                        UserHistory::where('user_id', Auth::user()->id)->update([ 'audio_id' => json_encode($audioId) ]);
                                    }else{
                                        UserHistory::create(['user_id' => Auth::user()->id, 'audio_id' => json_encode([$detail->id]) ]);
                                    }
                                }
                                
                                $artists_name = $this->getArtistName(['artist_id' => $detail->artist_id]);
                                if(!empty($detail->banner_image)){
                                    $banner_image = url('public/images/audio/thumb/'.$detail->banner_image);
                                }else{
                                    $banner_image = url('public/images/index_bg.png');
                                }
                                $songArr = (object)[
                                    'mid' => $detail->id,
                                    'mp3url' => ($detail->aws_upload == 1) ? $this->getSongAWSUrl($songDetail) : url('/public/images/audio/'.$detail->audio),
                                    'song_name' => $detail->audio_title,
                                    'artists' => $artists_name,
                                    'image' => url('/public/images/album/'.$album[0]->image),
                                    'banner_image' => $banner_image,
                                    'share_uri' => url('audio/single/'.$detail->id.'/'.$detail->audio_slug),
                                    'is_aws' => $detail->aws_upload,
                                    'status' => 'success'
                                ];
                            }
                            $resp[] = $songArr;
                        }
                    }
                }else{
                    $resp = ['status' => 'false', 'msg' => __('frontWords.no_song')];
                }
            }
            
            if($request->musictype == 'playlist'){ 
                $playlist = Playlist::where('id',$request->musicid)->select('song_list')->get(); 
                if(sizeof($playlist) > 0){
                    $countUpdate = Audio::where('id', $request->musicid)->update(['listening_count' => DB::raw('listening_count + 1')]);
                    $song = json_decode($playlist[0]['song_list']);
                    
                    if($song != ''){
                        for($i=0; $i<sizeof($song); $i++){
                            $songDetail = Audio::where('id', $song[$i])->get();
                            $songArr = (object)[];
                            if(sizeof($songDetail) > 0){
                                foreach($songDetail as $detail){
                                    
                                    if(isset(Auth::user()->id)){
                                        $checkHistory = UserHistory::where('user_id', Auth::user()->id)->get();
                                        if(sizeof($checkHistory) > 0){
                                            $audioId = json_decode($checkHistory[0]->audio_id);
                                            if(!in_array($detail->id, $audioId)){
                                                array_push($audioId, $detail->id);
                                            }
                                            UserHistory::where('user_id', Auth::user()->id)->update([ 'audio_id' => json_encode($audioId) ]);
                                        }else{
                                            UserHistory::create(['user_id' => Auth::user()->id, 'audio_id' => json_encode([$detail->id]) ]);
                                        }
                                    }
                                    $artists_name = $this->getArtistName(['artist_id' => $detail->artist_id]);
                                    if(!empty($detail->banner_image)){
                                        $banner_image = url('public/images/audio/thumb/'.$detail->banner_image);
                                    }else{
                                        $banner_image = url('public/images/index_bg.png');
                                    }
                                    $songArr = (object)[
                                        'mid' => $detail->id,
                                        'mp3url' => ($detail->aws_upload == 1) ? $this->getSongAWSUrl($songDetail) : url('/public/images/audio/'.$detail->audio),
                                        'song_name' => $detail->audio_title,
                                        'artists' => $artists_name,
                                        'image' => url('/public/images/audio/thumb/'.$detail->image),
                                        'banner_image' => $banner_image,
                                        'share_uri' => url('audio/single/'.$detail->id.'/'.$detail->audio_slug),
                                        'is_aws' => $detail->aws_upload,
                                        'status' => 'success'
                                    ];
                                }
                                $resp[] = $songArr;
                            }
                        }
                    }else{
                        $resp = ['status' => 'false', 'msg' => __('frontWords.no_song')];
                    }
                }else{
                    $resp = ['status' => 'false', 'msg' => __('frontWords.no_song')];
                }
            }
            
            if($request->musictype == 'audio'){
                
                if(isset(Auth::user()->id)){
                    $checkHistory = UserHistory::where('user_id', Auth::user()->id)->get();
                    if(sizeof($checkHistory) > 0){
                        $audioId = json_decode($checkHistory[0]->audio_id);
                        if(!in_array($request->musicid, $audioId)){
                            array_push($audioId, $request->musicid);
                        }
                        UserHistory::where('user_id', Auth::user()->id)->update([ 'audio_id' => json_encode($audioId) ]);
                    }else{
                        UserHistory::create(['user_id' => Auth::user()->id, 'audio_id' => json_encode([$request->musicid]) ]);
                    }
                }
                
                $audio = Audio::where('id',$request->musicid)->get();
                if(sizeof($audio) > 0){
                    $countUpdate = Audio::where('id', $request->musicid)->update(['listening_count' => DB::raw('listening_count + 1')]);
                    $artists_name = $this->getArtistName(['artist_id' => $audio[0]->artist_id]);
                    if(!empty($audio[0]->banner_image)){
                        $banner_image = url('public/images/audio/thumb/'.$audio[0]->banner_image);
                    }else{
                        $banner_image = url('public/images/index_bg.png');
                    }
                    $songArr = (object)[
                        'mid' => $audio[0]->id,
                        'mp3url' => ($audio[0]->aws_upload == 1) ? $this->getSongAWSUrl($audio) : url('/public/images/audio/'.$audio[0]->audio),
                        'song_name' => $audio[0]->audio_title,
                        'artists' => $artists_name,
                        'image' => url('/public/images/audio/thumb/'.$audio[0]->image),
                        'banner_image' => $banner_image,
                        'share_uri' => url('audio/single/'.$audio[0]->id.'/'.$audio[0]->audio_slug),
                        'is_aws' => $audio[0]->aws_upload,
                        'status' => 1
                    ];
                    $resp[] = $songArr;
                }else{
                    $resp = ['status' => 'false', 'msg' =>  __('frontWords.no_song') ];
                }
            }

            if($request->musictype == 'genre'){
                $audioData = Audio::where('audio_genre_id',$request->musicid)->get();
                $songArr = (object)[];
                if(sizeof($audioData) > 0){
                    foreach($audioData as $audio){
                        $artists_name = $this->getArtistName(['artist_id' => $audio->artist_id]);
                        if(!empty($audio->banner_image)){
                            $banner_image = url('/public/images/audio/thumb/'.$audio->banner_image);
                        }else{
                            $banner_image = url('/public/images/index_bg.png');
                        }
                        $songArr = (object)[
                            'mid' => $audio->id,
                            'mp3url' => ($audio->aws_upload == 1) ? $this->getSongAWSUrl($audioData) : url('/public/images/audio/'.$audio->audio),
                            'song_name' => $audio->audio_title,
                            'artists' => $artists_name,
                            'image' => url('/public/images/audio/thumb/'.$audio->image),
                            'banner_image' => $banner_image,
                            'share_uri' => url('audio/single/'.$audio->id.'/'.$audio->audio_slug),
                            'is_aws' => $audio->aws_upload,
                            'status' => 'success'
                        ];
                        $resp[] = $songArr;
                    }
                }else{
                    $resp = ['status' => 'false', 'msg' => __('frontWords.no_song')];
                }
            }

        }else{
            $resp[] = $checkValidate;
        }
        echo json_encode($resp); die();
    }

    function getArtistName($param){
        $artistName = json_decode($param['artist_id']);
        $artists = join(',', $artistName);
        $artist_name = Artist::whereIn('id', [$artists])->select('artist_name')->get();
        $artists_name = '';
        if(!empty($artist_name)){
            foreach($artist_name as $artist){
                $artists_name .= $artist->artist_name.', ';
            }
        }
        return rtrim($artists_name,', ');
    }


    function getSongAWSUrl($dataArr){
        $SrcDirectorty = env('AWS_DIRECTORY');
        $url = 'https://'.env('AWS_BUCKET').'.s3.amazonaws.com/'.$SrcDirectorty;
        $files = Storage::disk('s3')->files($SrcDirectorty);
        foreach ($files as $file) {
            if(str_replace($SrcDirectorty.'/', '', $file) == $dataArr[0]->audio){
                return $url.'/'.$dataArr[0]->audio;
            }
        }
    }


    public function album(Request $request){
        $setLang = $this->getUserLang();
        if($setLang != '' && sizeof($setLang) > 0){
            $data['albums'] = Album::whereIn('language_id', $setLang)->orderBy('id','desc')->limit(15)->get();     
        }else{
            $data['albums'] = Album::orderBy('id','desc')->limit(15)->get();
        }
        $data['top_album'] = TopDetail::select('top_album')->get(); 
        if($request->ajax()){        

            $html = view('ajax.album',$data)->render();
            $response['status'] = true;            
            $response['html'] = $html;
            return response()->json($response);
        }else{
            return view('front.album', $data)->render();
        }   
    }
    
    public function audio(Request $request){ 

        $setLang = $this->getUserLang();        
        $ytPlaylists = [];
        $playlistData = '';

        $is_youtube = Settings::where('name', 'is_youtube')->first();       

        if(!empty($is_youtube) && $is_youtube['value'] == 1 && !empty(env('YOUTUBE_CHANNEL_KEY'))) {           

            $ytPlaylists = Youtube::getPlaylistsByChannelId(env('YOUTUBE_CHANNEL_KEY'));

            if(!empty(env('YT_COUNTRY_CODE'))){
                $data['popularYtVideos'] = Youtube::getPopularVideos(env('YT_COUNTRY_CODE'));    
            }else{
                $data['popularYtVideos'] = []; 
            }

            $data['is_youtube'] = 1;

        }else{
            $data['is_youtube'] = 0;
        }

        if($setLang != '' && sizeof($setLang) > 0){            
            $playlistData = AdminPlaylist::where('status','1')->whereIn('audio_language', $setLang)->orderBy('id','desc')->get();     
            $data['trending_audio'] = Audio::where('is_trending',1)->whereIn('audio_language', $setLang)->orderBy('id','desc')->get();      
            $data['all_audios'] = Audio::whereIn('audio_language', $setLang)->orderBy('id','desc')->get();              
        }else{
            $playlistData = AdminPlaylist::where('status','1')->orderBy('id','desc')->get()->toArray();            
            $data['trending_audio'] = Audio::where('is_trending',1)->orderBy('id','desc')->get();
            $data['all_audios'] = Audio::orderBy('id','desc')->get();
        }    

        $data['ytPlaylists'] = $ytPlaylists;
        $data['top_album'] = TopDetail::select('top_album')->get();        
        
        $playlist_audios =array();
		 foreach($playlistData as $key => $value){
            $get_arr                    = array();
            $get_arr['playlist_title']  = $value['playlist_title'];
            $audio_id                   = json_decode($value['audio_id']);
            $get_arr['playlist_audio']  = Audio::whereIn('id',$audio_id)->orderBy('id','desc')->get();
            $playlist_audios[]          = $get_arr;
            
        }
        
        $data['admin_playlist'] = $playlist_audios;
        
        
        //$data['admin_playlist'] = $this->getAdminAddedPlaylist($playlistData);
        
        $data['top_audio'] = TopDetail::select('top_audio')->get(); 
        
        if($request->ajax()){        

            $html = view('ajax.audio',$data)->render();
            $response['status'] = true;            
            $response['html'] = $html;
            return response()->json($response);
        }else{

            return view('front.audio', $data)->render();            
        }    
    }

    public function artist(Request $request){

        $data['featured_artist'] = Artist::where('is_featured',1)->orderBy('id','desc')->get();
        $data['top_artist'] = TopDetail::select('top_artist')->get(); 
        if($request->ajax()){        

            $html = view('ajax.artist',$data)->render();
            $response['status'] = true;            
            $response['html'] = $html;
            return response()->json($response);
        }else{
            return view('front.artist', $data)->render();
        }                
    }

    public function favourite(Request $request){
        $data['favourites'] = [];
        if(isset(Auth::user()->id)){
            $data['favourites'] = Favourite::where('user_id', Auth::user()->id)->get();
            //$data['favourites'] = $this->getUserFavouriteSongs($favourites);
        }
        $setLang = $this->getUserLang();
        if($setLang != '' && sizeof($setLang) > 0){
            $data['recentlyAddedAudio'] = Audio::whereIn('audio_language', $setLang)->orderBy('id', 'desc')->limit(15)->get();
        }else{
            $data['recentlyAddedAudio'] = Audio::orderBy('id', 'desc')->limit(15)->get();
        }

        if($request->ajax()){        

            $html = view('ajax.favourite',$data)->render();
            $response['status'] = true;            
            $response['html'] = $html;
            return response()->json($response);
        }else{
            return view('front.favourite', $data)->render();
        }     
    }

    public function genres(Request $request){

        $data['genres'] = AudioGenre::where('status',1)->get();

        if($request->ajax()){     
            $html = view('ajax.genres',$data)->render();
            $response['status'] = true;
            $response['html'] = $html;
            return response()->json($response);
        }else{
            return view('front.genres',$data)->render();
        }      
    }

    public function paymentSingle($id){
        $id = Crypt::decrypt($id);
        $data['plan_detail'] = Plan::find($id);
        $data['defaultCurrency'] = Currency::all();
        $data['rate'] = currencyRate::fetchRate();
        $data['paymentMethod'] = PaymentMethod::pluck('status','gateway_name')->all();
        if(!empty($data['plan_detail']))
            return view('front.paymentSingle', $data);
        else{
            return redirect('pricing-plan');
        }
    }

    public function history(Request $request){
        $data['audios'] = []; 
        if(isset(Auth::user()->id)){
            $getAudio = UserHistory::where('user_id', Auth::user()->id)->get();
            if(sizeof($getAudio) > 0){
                $audioId = array_reverse(json_decode($getAudio[0]->audio_id));
                $data['audios'] = $audioId;
            }
        }
        if($request->ajax()){        

            $html = view('ajax.history',$data)->render();
            $response['status'] = true;            
            $response['html'] = $html;
            return response()->json($response);
        }else{
            return view('front.history', $data)->render();
        }     
    }

    public function download(){
        return view('front.download');
    }

    public function free_music(){
        return view('front.free_music');
    }

    public function radio_station(){
        $setLang = $this->getUserLang();
        if($setLang != '' && sizeof($setLang) > 0){
            $data['radios'] = Radio::whereIn('language_id', $setLang)->orderBy('id','desc')->get();
        }else{
            $data['radios'] = Radio::orderBy('id','desc')->get();
        }        
        return view('front.radio', $data);
    }

    public function album_single(Request $request, $id, $slug){
        $data['album'] = Album::where(['id' => $id, 'album_slug' => $slug])->first();

        if($request->ajax()){        

            $html = view('ajax.album_single',$data)->render();
            $response['status'] = true;            
            $response['html'] = $html;
            return response()->json($response);
        }else{
            return view('front.album_single', $data)->render();            
        } 
    }

    public function artist_single(Request $request, $id, $slug){
        $data['favourite'] = '0';
        $data['artist'] = Artist::where(['id' => $id, 'artist_slug' => $slug])->get();
        
        $checkYtChannelId = ArtistIntegrationSetting::where('artist_id',$id)->first();
        $data['youtube_status'] = 0;
        $data['artistYtPlaylists'] = '';
        if(!empty($checkYtChannelId)){
            if($checkYtChannelId->youtube_status == 1 && !empty($checkYtChannelId->google_api_key) && !empty($checkYtChannelId->youtube_channel_key)) {
                Youtube::setApiKey($checkYtChannelId->google_api_key);
                $artistYtPlaylists = Youtube::getPlaylistsByChannelId($checkYtChannelId->youtube_channel_key);
                if(!empty($artistYtPlaylists['results'])){
                    $data['artistYtPlaylists'] = $artistYtPlaylists['results'];
                }
                $data['youtube_status'] = 1;
            }
        } 

        if(isset(Auth::user()->id)){ 
            $getData = Favourite::where(['user_id'=> auth()->user()->id])->get();
            
            if(sizeof($getData) > 0){
                $decodeIds = $getData[0]->artist_id;  
                if($decodeIds != '' && !empty($decodeIds)){
                    $dataId = json_decode($decodeIds);
                    if( in_array($id, $dataId) ) {
                        $data['favourite'] = '1';
                    }
                }                
            }
        }            

        $data['getSong'] = [];
        if(sizeof($data['artist']) > 0){
            $data['getSong'] = AudioArtist::where('artist_id',$data['artist'][0]->id)->get();
        }
        if($request->ajax()){       
            
            $html = view('ajax.artist_single',$data)->render();
            $response['status'] = true;
            $response['html'] = $html;
            return response()->json($response);
        }else{
            return view('front.artist_single', $data)->render();
        }      
    }

    public function audio_single(Request $request, $id, $slug){

        $setLang = $this->getUserLang();
        if($setLang != '' && sizeof($setLang) > 0){
            $data['audio'] = Audio::whereIn('audio_language', $setLang)->where(['id' => $id, 'audio_slug' => $slug])->get();
            $data['similar_audio'] = Audio::whereIn('audio_language', $setLang)->where('id','!=',$id)->get();
        }else{
            $data['audio'] = Audio::where(['id' => $id, 'audio_slug' => $slug])->get();
            $data['similar_audio'] = Audio::where('id','!=',$id)->get();
        }   
        $data['audio_id'] = $id;
        $data['is_single'] = 1;
        $data['comments'] = Comment::where('audio_id', $id)->inRandomOrder()->limit(20)->get();

        if($request->ajax()){       

            $html = view('ajax.audio_single',$data)->render();
            $response['status'] = true;            
            $response['html'] = $html;
            return response()->json($response);
        }else{
            return view('front.audio_single', $data)->render();
        } 
    }

 
    public function ytplaylist_single(Request $request, $id){

        $data = [];
        $data['is_youtube'] = 0;
        $is_youtube = Settings::where('name', 'is_youtube')->first();       

        if(!empty($is_youtube) && $is_youtube['value'] == 1){
            $playlist = Youtube::getPlaylistItemsByPlaylistId($id);
            $playlistItems = $playlist['results'];
            $data['playlistItems'] = $playlistItems; 
            $data['is_youtube'] = 1;
        }

        if($request->ajax()){       

            $html = view('ajax.ytplaylist-single',$data)->render();
            $response['status'] = true;            
            $response['html'] = $html;
            return response()->json($response);
        }else{
            return view('front.ytplaylist-single', $data)->render();
        } 
    }

    public function genre_single(Request $request, $id, $slug){
        
        $setLang = $this->getUserLang();
        $data['genres'] = AudioGenre::where(['id' => $id, 'genre_slug' => $slug])->first();
        if($setLang != '' && sizeof($setLang) > 0){
            $data['audioData'] = Audio::whereIn('audio_language', $setLang)->where('audio_genre_id', $id)->select('id')->get();
        }else{
            $data['audioData'] = Audio::where('audio_genre_id', $id)->select('id')->get();
        }  
        
        if($request->ajax()){     
            $html = view('ajax.genre_single',$data)->render();
            $response['status'] = true;
            $response['html'] = $html;
            return response()->json($response);
        }else{
            return view('front.genre_single', $data)->render();
        }
    }

    public function blog_single(Request $request, $id, $slug){
        $data['blogCategories'] = BlogCategories::where('is_active',1)->get()->toArray();
        $data['blog'] = Blogs::where(['id' => $id, 'slug' => $slug])->get();
        $data['comments'] = Comment::where(['blog_id'=> $id])->get();
        if($request->ajax()){     
            $html = view('ajax.blog_single',$data)->render();
            $response['status'] = true;
            $response['html'] = $html;
            return response()->json($response);
        }else{
            return view('front.blog_single', $data)->render();
        }
    }
        
    public function blog_multiple(Request $request, $id = null){
        $data['blogs'] = Blogs::where(['blog_cat_id' => $id])->get();
        if($request->ajax()){     
            $html = view('ajax.multiple_blog',$data)->render();
            $response['status'] = true;
            $response['html'] = $html;
            return response()->json($response);
        }else{
            return view('front.multiple_blog', $data)->render();
        }
    }
    
    public function add_favourite_list(Request $request, $type){
        
        if($type == 'album'){
            $rules = ['albumid' => 'required'];
            $id = $request->albumid;
            $column = 'album_id';
        }else if($type == 'artist'){
            $rules = ['artistid' => 'required'];
            $column = 'artist_id';
            $id = $request->artistid;
        }else if($type == 'audio'){
            $rules = ['audioid' => 'required'];
            $id = $request->audioid;
            $column = 'audio_id';
        }else if($type == 'radio'){
            $rules = ['radioid' => 'required'];
            $id = $request->radioid;
            $column = 'radio_id';
        }else if($type == 'playlist'){
            $rules = ['playlistid' => 'required'];
            $id = $request->playlistid;
            $column = 'playlist_id';
        }else if($type == 'genre'){
            $rules = ['genreid' => 'required'];
            $id = $request->genreid;
            $column = 'genre_id';
        }
        
        $checkValidate = validation($request->except('_token'), $rules);
        if($checkValidate['status'] == 1){
            if(isset(Auth::user()->id)){
                $datas = $dataId = array();
                $userid = Auth::user()->id;
                $datas[] = $id;
                $getData = Favourite::where(['user_id'=> $userid])->get();
                if(sizeof($getData) > 0){
                    if($type == 'album'){
                        $decodeIds = $getData[0]->album_id;
                    }else if($type == 'artist'){
                        $decodeIds = $getData[0]->artist_id;
                    }else if($type == 'audio'){
                        $decodeIds = $getData[0]->audio_id;
                    }else if($type == 'radio'){
                        $decodeIds = $getData[0]->radio_id;
                    }else if($type == 'playlist'){
                        $decodeIds = $getData[0]->playlist_id;
                    }else if($type == 'genre'){
                        $decodeIds = $getData[0]->genre_id;
                    }

                    if($decodeIds != '' && !empty($decodeIds)){
                        $dataId = json_decode($decodeIds);
                    }
                    
                    if( in_array($id, $dataId) ) {
                        $key = array_search($id, $dataId); 
                        unset($dataId[$key]);
                        $new_arr = array_values($dataId);
                        $update = Favourite::where('user_id', $userid)->update([$column => json_encode($new_arr)]);

                        $resp = ($update) ? ['status' => 1, 'msg' => __('frontWords.remove_success'), 'action'=>'removed'] : ['status' => 0, 'msg' => __('frontWords.something_wrong')];
                    }else{
                        $new_arr = array_merge($dataId, $datas);
                        $create_album = Favourite::where(['user_id'=>$userid])->update([$column=>json_encode($new_arr)]);

                        $resp = ($create_album) ? ['status' => 1, 'msg' => __('frontWords.add_success'), 'action'=>'added'] : ['status' => 0, 'msg' => __('frontWords.something_wrong')];
                    }
                }else{
                    $create_album = Favourite::create([$column => json_encode($datas), 'user_id'=>$userid]);
                    $resp = ($create_album) ? ['status' => 1, 'msg' => __('frontWords.add_success') , 'action'=>'added'] : ['status' => 0, 'msg' => __('frontWords.something_wrong')];
                }
            }else{
                $resp = ['status' => 0, 'msg' => __('frontWords.login_err')];
            }
        }else{
            $resp = $checkValidate;
        }
	    echo json_encode($resp);
    }


    public function filter_music_language(Request $request){
        
        $language = $request->filter_lang;
        if(isset($language)){
            if( isset(Auth::user()->id)){
                $userid = Auth::user()->id;
                $lang_data = [];
                if( $language != '' ){
                    Session::forget('audio_language');
                    $lang_data = explode(',', $language);
                   
                    $getData = Favourite::where(['user_id'=> $userid])->get();
                   
                    if(count($getData) > 0){
                        $updateLang = Favourite::where(['user_id'=>$userid])->update(['user_language'=>json_encode($lang_data)]);
                        $resp = ($updateLang) ? ['status' => 1] : ['status' => 0, 'msg' => __('frontWords.something_wrong')];
                    }else{
                        $createLang = Favourite::create(['user_language'=>json_encode($lang_data), 'user_id' => $userid]);
                        $resp = ($createLang) ? ['status' => 1] : ['status' => 0, 'msg' => __('frontWords.something_wrong')];
                    }
                }else{
                    $updateLang = Favourite::where(['user_id'=>$userid])->update(['user_language'=>json_encode($lang_data)]);
                    $resp = ($updateLang) ? ['status' => 1] : ['status' => 0, 'msg' => __('frontWords.something_wrong')];
	            }
                echo json_encode($resp);
	        }else{
                if(\Cookie::get('lang_filter') != ''){
                    return \Cookie::forget('lang_filter');
                }
                
                if(gettype($language) == 'string'){
                    $langauge = [$language];
                }
                echo json_encode(['status' => 1]);
                return \Cookie::make('lang_filter', $language, time() + (86400 * 30) );
	        }
        }else{
            echo json_encode(['status' => 1]);
            return \Cookie::forget('lang_filter');
        }
		die();
	}

    public function like_dislike_audio(Request $request){
        if(Auth::check()){
            $userid = Auth::user()->id;
            $checkValidate = validation($request->except('_token'), ['id'=>'required','type'=>'required']);
            if($checkValidate['status'] == 1){
                $checkAudio = UserAction::where(['user_id'=>$userid, 'audio_id'=>$request->id])->get();
                if(count($checkAudio) > 0){
                    if($checkAudio[0]->like && $request->type == 1){ // like remove kr rha hai
                        $value = ['like' => 0];
                        $updateValue = ['like' => 0, 'like_count'=> DB::raw('like_count - 1')];
                    }
                    else if($checkAudio[0]->dislike && $request->type == 2){ // dislike remove kr rha hai
                        $value = ['dislike' => 0];
                        $updateValue = ['dislike' => 0, 'dislike_count'=> DB::raw('dislike_count - 1')];
                    }
                    else if($checkAudio[0]->dislike && $request->type == 1){ // dislike hai or like kr rha hai
                        $value = ['dislike' => 0, 'like' => 1];
                        $updateValue = ['dislike' => 0, 'like' => 1, 'like_count'=> DB::raw('like_count + 1'), 'dislike_count' => DB::raw('dislike_count - 1')];
                    }
                    else if($checkAudio[0]->like && $request->type == 2){ // like hai or dislike kr rha hai
                        $value = ['dislike' => 1, 'like' => 0];
                        $updateValue = ['dislike' => 1, 'like' => 0, 'like_count'=> DB::raw('like_count - 1'), 'dislike_count' => DB::raw('dislike_count + 1')];
                    }
                    else if($request->type == 1){ // like kr rha hai
                        $value = ['like' => 1];
                        $updateValue = ['like' => 1, 'like_count'=> DB::raw('like_count + 1')];
                    }
                    else if($request->type == 2){ // dislike kr rha hai
                        $value = ['dislike' => 1];
                        $updateValue = ['dislike' => 1, 'dislike_count'=> DB::raw('dislike_count + 1')];
                    }
                    
                    $update = UserAction::where(['user_id'=>$userid,  'audio_id'=>$request->id])->update($updateValue);
                    $checkNegative = UserAction::where(['user_id'=>$userid, 'audio_id'=>$request->id])->get();
                    if(!empty($checkNegative)){
                        $dataa = [];
                        if($checkNegative[0]->like_count == -1){
                            $dataa['like_count'] = 0;
                        }
                        if($checkNegative[0]->dislike_count == -1){
                            $dataa['dislike_count'] = 0;
                        }
                        $update = UserAction::where(['user_id'=>$userid,  'audio_id'=>$request->id])->update($dataa);
                    }
                    $resp = ['status'=> 1, 'resp'=>$value];
                }else{
                    if($request->type == 1){
                        $updateValue = ['user_id'=>$userid, 'audio_id'=>$request->id, 'like'=>1, 'like_count'=> DB::raw('like_count + 1')];
                        $value= ['like'=>1];
                    }else if($request->type == 2){
                        $updateValue = ['user_id'=>$userid, 'audio_id'=>$request->id, 'dislike'=>1, 'dislike_count'=> DB::raw('dislike_count + 1')];
                        $value= ['dislike'=>1];
                    }
                    $create = UserAction::create($updateValue);
                    if($create)
                        $resp = ['status'=> 1, 'resp'=>$value];
                    else
                        $resp = ['status'=> 0, 'resp'=> __('frontWords.something_wrong')];
                }
            }else{
                $resp = $checkValidate;
            }
        }else{
            $resp = ['status' => 0, 'msg' =>__('frontWords.login_err')];
        }
        echo json_encode($resp);
    }

    public function download_track(Request $request){
        $checkValidate = validation($request->except('_token'), ['musicid'=>'required']);
        if($checkValidate['status'] == 1){
            if(isset(Auth::user()->id)){
                $mp3url = ''; $title = '';
                $checkType = Audio::find($request->musicid);
                if(!empty($checkType)){
                    if($checkType->aws_upload == 0){
                        $mp3url = url('public/images/audio/'.$checkType->audio);
                    }
                    $title = $checkType->audio_title;
                }
                $resp = ['status' => 1, 'mp3_uri'=>$mp3url, 'mp3_name' => $title];
            }else{
                $resp = ['status'=>0, 'msg' => __('frontWords.login_err')];
            }
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }
    
    
    public function download_list(Request $request,$type=null){
        
        $path = public_path('zipped_audio_file');
        $zip      = new ZipArchive;
        $fileName = '';
        $file = '';
        $song = '';
        
        if($type == 'playlist'){
            $playlist = Playlist::where('id',$request->musicid)->select('song_list','playlist_name')->get(); 
            if(sizeof($playlist) > 0){
                $song = json_decode($playlist[0]['song_list']);
                $fileName = $playlist[0]->playlist_name.'.zip';
            }
        }
        if($type == 'album'){
            $album = Album::where('id',$request->musicid)->select('song_list','album_name')->get(); 
            if(sizeof($album) > 0){
                $song = json_decode($album[0]['song_list']);
                $fileName = $album[0]->album_name.'.zip';
            }
        }
        if($type == 'artist'){
            $audioArtist = AudioArtist::where('artist_id',$request->musicid)->select('audio_id')->get();
            $artist = Artist::where('id',$request->musicid)->select('artist_name')->get();
            if(sizeof($audioArtist) > 0 && sizeof($artist) > 0){
                $song = json_decode($audioArtist[0]['audio_id']);
                $fileName = $artist[0]->artist_name.'.zip';
            }
        }
        
        if(File::exists(public_path($path.'/'.$fileName))){
            unlink(public_path($path.'/'.$fileName));
        } 

        if(!File::isDirectory($path)){
            File::makeDirectory($path, 0777, true, true);
        }
       
        if(!empty($song)){
            if ($zip->open(public_path('zipped_audio_file/'.$fileName), ZipArchive::CREATE) === TRUE) {
                for($i=0; $i<sizeof($song); $i++){
                    $songDetail = Audio::where('id', $song[$i])->get();
                    if(!empty($songDetail)){
                        if($songDetail[0]->aws_upload == 0){
                            $zip->addFile(public_path('images/audio/'.$songDetail[0]->audio), $songDetail[0]->audio_title);
                        }
                    }
                }
                $zip->close();
                $file = 'zipped_audio_file/'.$fileName; 
            }
            $resp = ['status' => 1, 'mp3_uri'=>$file, 'mp3_name' => $fileName, 'msg' => 'Download in progress'];
        }else{
            $resp = ['status' => 'false', 'msg' => __('frontWords.no_song')];
        }
        
        if($type == 'genre'){
            $songDetail = Audio::where('audio_genre_id',$request->musicid)->get();
            $audioGenre = AudioGenre::where('id' , $request->musicid)->select('genre_name')->get();
            $fileName = $audioGenre[0]->genre_name.'.zip';
            if ($zip->open(public_path('zipped_audio_file/'.$fileName), ZipArchive::CREATE) === TRUE) {
                if(File::exists(public_path($path.'/'.$fileName))){
                    unlink(public_path($path.'/'.$fileName));
                } 
        
                if(!File::isDirectory($path)){
                    File::makeDirectory($path, 0777, true, true);
                }
                if(sizeof($songDetail) > 0){
                    foreach($songDetail as $song){
                        if($song->aws_upload == 0){
                            $zip->addFile(public_path('images/audio/'.$song->audio), $song->audio_title);
                        }
                    }
                    $zip->close();
                    $file = 'zipped_audio_file/'.$fileName;
                    $resp = ['status' => 1, 'mp3_uri'=>$file, 'mp3_name' => $fileName, 'msg' => 'Download in progress'];
                }else{
                    $resp = ['status' => 'false', 'msg' => __('frontWords.no_song')];
                }
            }
            
        }
        echo json_encode($resp);
    }
    
    function downloadaudio(Request $request){
        if(isset(Auth::user()->id)){
            if($request->path != 'undefined'){
                $path = urldecode($request->path);
                $ch = curl_init($path);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_NOBODY, 0);
                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
                $output = curl_exec($ch);
                $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                
                if ($status == 200) {
                    header("Content-type: application/octet-stream");
                    header("Content-Disposition: attachment; filename=".$request->name.'.'.pathinfo($path, PATHINFO_EXTENSION));
                    echo $output;
                }
            }
        }else{
            toastr()->error(__('frontWords.login_err'), '', ['timeOut' => 2000, 'progressBar' =>false]);
            return redirect('home');
        }
    }
    
    
    public function playlist(Request $request){ 
        if(isset(Auth::user()->id)){
            $data['playlist'] = Playlist::where('user_id' , Auth::user()->id)->get();
        }else{
            $data['playlist'] = [];
        }
        if($request->ajax()){        

            $html = view('ajax.playlist')->render();          
            $response['status'] = true;            
            $response['html'] = $html;
            return response()->json($response);
        }else{
            return view('front.playlist', $data)->render();
        }     
    }
    
    
    public function create_playlist(Request $request){
        if(isset(Auth::user()->id)){
            $checkValidate = validation($request->except('_token'), ['playlist_name' => 'required']);
            if($checkValidate['status'] == 1){
                $playlist = Playlist::where('playlist_name', $request->playlist_name)->get();
                if(count($playlist) > 0){
                    $resp = ['status'=>0, 'msg' => __('frontWords.playlist').' '.__('frontWords.already_exist') ];
                }else{
                    $create = Playlist::create(['user_id' => Auth::user()->id, 'playlist_name' => $request->playlist_name]);                    
                    $html = view('ajax.create_playlist')->render();                    
                    $resp = ['status' => 1, 'msg' => __('frontWords.playlist').' '.__('frontWords.created_success'), 'html' => $html ];
                }
            }else{
                $resp = $checkValidate;
            }
        }else{
            $resp = ['status'=>0, 'msg' => __('frontWords.login_err') ];
        }
        echo json_encode($resp);
    }

    public function play_playlist_song(Request $request){
        $playlist = Playlist::where('id',$request->musicid)->select('song_list')->get();
        if(count($playlist) > 0){
            $song = json_decode($playlist[0]['song_list']);
            if(!empty($song) && $song != ''){
                for($i=0; $i<count($song); $i++){
                    $setLang = $this->getUserLang();
                    if($setLang != '' && sizeof($setLang) > 0){
                        $songDetail = Audio::whereIn('audio_language', $setLang)->where('id', $song[$i])->get();
                    }else{
                        $songDetail = Audio::where('id', $song[$i])->get();
                    }  
                    $songArr = [];
                    if(!empty($songDetail)){
                        foreach($songDetail as $detail){
                            $artists_name = $this->getArtistName(['artist_id' => $detail->artist_id]);
                            $songArr = (object)[
                                'mid' => $detail->id,
                                'mp3url' => ($detail->aws_upload == 1) ? $this->getSongAWSUrl($songDetail) : url('/public/images/audio/'.$detail->audio),
                                'song_name' => $detail->audio_title,
                                'artists' => $artists_name,
                                'image' => url('/public/images/audio/'.$detail->image),
                                'share_uri' => url('audio/single/'.$detail->id.'/'.$detail->audio_slug),
                                'is_aws' => $detail->aws_upload,
                                'status' => 'success'
                            ];
                        }
                    }
                    $resp[] = $songArr; 
                }
            }else{
                $resp = ['status'=>0, 'msg'=> __('frontWords.empty_playlist') ];
            }
        }
        echo json_encode($resp); 
    }

    public function add_in_playlist(Request $request){

        if(isset(Auth::user()->id)){

            $checkValidate = validation($request->except('_token'), ['playlistid'=>'required', 'musicid' => 'required']);
            if($checkValidate['status'] == 1){
                $songs[] = $request->musicid;
                $checkPlaylist = Playlist::where('id', $request->playlistid)->get();
                if(!empty($checkPlaylist)){
                    if($request->type == 'ms_audio'){
                        $checkSongList = json_decode($checkPlaylist[0]->song_list);
                        if(!empty($checkSongList)){
                            if(!in_array( $request->musicid, $checkSongList)){
                                $song_list = array_merge($checkSongList, $songs);
                            }else{
                                echo json_encode(['status' => 0, 'msg' => __('frontWords.track').' '.__('frontWords.already_exist') ]); exit;
                            }
                        }else{
                            $song_list = [$request->musicid];
                        }
                        $update = Playlist::where('id', $request->playlistid)->update(['song_list'=>json_encode($song_list)]);
                    }elseif($request->type == 'ms_video'){ 
                        $checkSongList = json_decode($checkPlaylist[0]->video_list);
                        if(!empty($checkSongList)){
                            if(!in_array( $request->musicid, $checkSongList)){
                                $song_list = array_merge($checkSongList, $songs);
                            }else{
                                echo json_encode(['status' => 0, 'msg' => __('frontWords.track').' '.__('frontWords.already_exist') ]); exit;
                            }
                        }else{
                            $song_list = [$request->musicid];
                        }
                        $update = Playlist::where('id', $request->playlistid)->update(['video_list'=>json_encode($song_list)]);
                    }



                    $resp = ['status' => 1, 'msg' => __('frontWords.track').' '.__('frontWords.playlist_add') ];
                }else{
                    $resp = ['sttaus' => 0, 'msg' => __('frontWords.something_wrong')];
                }
            }else{
                $resp = $checkValidate;
            }
        }else{
            $resp = ['status' => 0, 'msg' => __('frontWords.login_err')];
        }
        echo json_encode($resp);
    }   

    public function remove_playlist(Request $request){
        if(isset(Auth::user()->id)){
            $checkValidate = validation($request->except('_token'), ['playlistid' => 'required']);
            if($checkValidate['status'] == 1){
                $playlist = Playlist::find($request->playlistid);
                if(!empty($playlist)){
                    $delete = $playlist->delete();
                    $resp = ['status'=> 1, 'msg'=> __('frontWords.playlist').' '.__('frontWords.remove_success') ];
                }else{
                    $resp = ['status'=>0, 'msg'=>__('frontWords.something_wrong')];
                }
            }else{
                $resp = $checkValidate;
            }
        }else{
            $resp = ['status' => 0, 'msg' => __('frontWords.login_err')];
        }
        echo json_encode($resp);
    }

    public function playlist_single(Request $request,$id){
        $data['playlist_name'] = '';
        $data['playlist_id'] = '';
        $data['getPlaylist'] = Playlist::where('id',$id)->get();

        if($request->ajax()){        
            $html = view('ajax.playlist-single',$data)->render();
            $response['status'] = true;            
            $response['html'] = $html;
            return response()->json($response);
        }else{
            return view('front.playlist-single', $data)->render();
        } 
    }

    public function remove_music_from_playlist(Request $request){
        if(isset(Auth::user()->id)){
            $checkValidate = validation($request->except('_token'), ['songid'=>'required','listid' => 'required']);
            if($checkValidate['status'] == 1){               

                $songs[] = $request->songid;
                $checkPlaylist = Playlist::where('id', $request->listid)->get();
                if(!empty($checkPlaylist)){
                    if($request->musictype == 'ms_audio'){
                        $songList = json_decode($checkPlaylist[0]->song_list);
                        if(in_array($request->songid, $songList)){
                            $key = array_search($request->songid, $songList);
                            unset($songList[$key]);
                            $newArr = array_values($songList);
                            $updateList = Playlist::where('id', $request->listid)->update(['song_list'=> $songList]);
                            $resp = ['status'=>1, 'msg'=>__('frontWords.track').' '.__('frontWords.remove_success')];
                        }                        
                    }elseif($request->musictype == 'ms_video'){
                        $videoList = json_decode($checkPlaylist[0]->video_list);
                        if(in_array($request->songid, $videoList)){
                            $key = array_search($request->songid, $videoList);
                            unset($videoList[$key]);
                            $newArr = array_values($videoList);
                            $updateList = Playlist::where('id', $request->listid)->update(['video_list'=> $videoList]);
                            $resp = ['status'=>1, 'msg'=>__('frontWords.track').' '.__('frontWords.remove_success')];
                        }                        
                    }
                }else{
                    $resp = ['status'=>0, 'msg'=>__('frontWords.something_wrong')];
                }
            }else{
                $resp = $checkValidate;
            }
        }else{
            $resp = ['status' => 0, 'msg' => __('frontWords.login_err')];
        }
        echo json_encode($resp);
    }


    public function newsletter(Request $request){
        $checkValidation = validation($request->except('_token'), ['name'=>'required','email'=>'required']);
        if($checkValidation['status'] == 1){
            $subscribe = Newsletter::subscribe($request->email, ['FNAME' => $request->name]);
            if(isset($subscribe) && $subscribe['status'] == 'subscribed'){
                $resp = ['status'=>1, 'msg' => __('frontWords.user').' '.__('frontWords.subscribe_success')];
            }else{
                $resp = ['status'=>0, 'msg' => __('frontWords.subscribe_err') ];    
            }
        }else{
            $resp = $checkValidation;
        }
        echo json_encode($resp);
    }

    public function playSongCount(Request $request){
        $checkValidate = validation($request->except('_token'), ['id'=>'required']);
        if($checkValidate['status'] == 1){
            $audio = Audio::find($request->id);
            if(!empty($audio)){
                $update = $audio->update(['listening_count' => DB::raw('listening_count + 1')]);

                if(isset(Auth::user()->id)){
                    $user_id = Auth::user()->id;
                    $checkHistory = UserHistory::where('user_id', $user_id)->get();
                    if(sizeof($checkHistory) > 0){
                        $audioId = json_decode($checkHistory[0]->audio_id);
                        if(!in_array($request->id, $audioId)){
                            array_push($audioId, $request->id);
                        }
                        $updateSong = UserHistory::where('user_id', $user_id)->update([ 'audio_id' => json_encode($audioId) ]);
                    }else{
                        $updateSong = UserHistory::create(['user_id' => $user_id, 'audio_id' => json_encode([$request->id]) ]);
                    }
                }
                if($update){
                    $resp = ['status'=>1, 'msg'=>__('frontWords.update_success')];
                }else{
                    $resp = ['status'=>0, 'msg'=>__('frontWords.something_wrong')];
                }
            }else{
                $resp = ['status'=>0, 'msg'=>__('frontWords.something_wrong')];
            }
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

    public function top_detail_cron_job(){
        $getTopAlbum = Album::where('listening_count','>','0')->orderBy('listening_count','desc')->limit(15)->get();
        $checkData = TopDetail::all();    
        $add = 0;
        if(!empty($getTopAlbum)){
            $albumArr = [];
            foreach($getTopAlbum as $album){
                array_push($albumArr, $album->id);
            }
            if(!empty($albumArr) && sizeof($checkData) > 0){
                $update = TopDetail::where('id',$checkData[0]->id)->update(['top_album'=>json_encode($albumArr)]);
            }else{
                $add = TopDetail::create(['top_album'=>json_encode($albumArr)]);
            }
        }
        $id = ($add) ? $add->id : $checkData[0]->id;
             
        $getTopArtist = Artist::where('listening_count','>','0')->orderBy('listening_count','desc')->limit(10)->get();
        
        if(!empty($getTopArtist)){
            $artistArr = [];
            foreach($getTopArtist as $artist){
                array_push($artistArr, $artist->id);
            }
            if(!empty($artistArr)){
                $update = TopDetail::where('id',$id)->update(['top_artist'=>json_encode($artistArr)]);
            }
        }

        $getTopAudio = Audio::where('listening_count','>','0')->orderBy('listening_count','desc')->limit(15)->get();
        if(!empty($getTopAudio)){
            $audioArr = [];
            foreach($getTopAudio as $audio){
                array_push($audioArr, $audio->id);
            }
            if(!empty($audioArr)){
                $update = TopDetail::where('id',$id)->update(['top_audio'=>json_encode($audioArr)]);
            }
        }
    }

    public function user_comment(Request $request, $type, $id){
        if(isset(Auth::user()->id)){
            $checkValidate = validation($request->except('_token'), ['message' => 'required']);
            if($checkValidate['status'] == 1){
                $arr = [
                    'user_id' =>Auth::user()->id,
                    $type.'_id' => $id,
                    'message' => $request->message,
                    'status' => '1'
                ];
                $addComment = Comment::create($arr);
                if($addComment){
                    $resp = ['status' => 1, 'msg' => __('frontWords.comment').' '.__('frontWords.save_success') ];
                }else{
                    $resp = ['status' => 0, 'msg' => __('frontWords.something_wrong')];
                }
            }else{
                $resp = $checkValidate;
            }
        }else{
            $resp = ['status' => 0, 'msg' => __('frontWords.login_err') ];
        }       
        //return response()->json($resp); 
        echo json_encode($resp);
    }   

    public function user_profile(Request $request){
        if(isset(Auth::user()->id)){
            $data['user'] = User::find(Auth::user()->id);
            if($request->ajax()){        
                $html = view('ajax.profile',$data)->render();
                $response['status'] = true;            
                $response['html'] = $html;
                return response()->json($response);
            }else{
                return view('front.profile', $data)->render();
            } 
        }else{
            return redirect('/home');
        }
    }

    public function update_profile(Request $request){
        $checkValidate = validation($request->except('_token'), ['user_name'=>'required']);
        if($checkValidate['status'] == 1){
            $checkUser = User::find(Auth::user()->id);
            if(!empty($checkUser)){
                $arr = ['name' => Purify::clean($request->user_name), 'billing_detail' => json_encode(['billing_name' => Purify::clean($request->billing_name), 'billing_email' => Purify::clean($request->billing_email), 'billing_contact' => Purify::clean($request->billing_contact), 'billing_address' => Purify::clean($request->billing_address) ])];
                if($request->user_password != ''){
                    $arr['password'] = Hash::make($request->user_password);
                }
                
                if($image = $request->file('user_image')){
                    $name = 'user'.$checkUser->id.'-'.time().'.'.$image->getClientOriginalExtension();
                    $arr['image'] = str_replace(' ','',$name);
                    upload_image($image, public_path().'/images/user/', $arr['image']);
                    if(!empty($checkUser) && $checkUser->image != ''){
                        delete_file_if_exist(public_path().'/images/user/'.$checkUser->image);
                    }
                }
            
                $update = $checkUser->update($arr);
                $resp = ['status' => 1, 'msg' => __('frontWords.detail').' '.__('frontWords.save_success')];
            }else{
                $resp = ['status' => 0, 'msg' => __('frontWords.something_wrong')];
            }
        }else{
            $resp = $checkValidate;
        }
        echo json_encode($resp);
    }

    public function pricing_plan(Request $request){
        $data['plans'] = Plan::where('status', 1)->get();
        $data['payments'] = PaymentMethod::where('status', 1)->get();
        $data['currency'] = Currency::get();
        $data['rate'] = currencyRate::fetchRate();
        if($request->ajax()){     
            $html = view('ajax.pricing',$data)->render();
            $response['status'] = true;
            $response['html'] = $html;
            return response()->json($response);
        }else{
            return view('front.pricing', $data)->render();
        }
    }

    public function search(Request $request, $name){

        $ytBrowseSearch = [];
        $data = [];

        $is_youtube = Settings::where('name', 'is_youtube')->first();       

        if(!empty($is_youtube) && $is_youtube['value'] == 1){                    

            // $video = Youtube::getVideoInfo('rie-hPVJ7Sw');
            // print_r($video); die('--video');

            $params = [
                'q'             => $name,
                'type'          => 'Songs',
                'part'          => 'id, snippet',
                'maxResults'    => 200
            ]; 
            $ytMusic = Youtube::searchAdvanced($params, true);
            $ytBrowseSearch = $ytMusic['results'];            

            $data['is_youtube'] = 1;
        }else{
            $data['is_youtube'] = 0;
        }        

        $userLanguage = $this->getUserLang();        
        
        $data['search'] = $name;
        if(isset(Auth::user()->id)){
            $data['albums'] = Album::whereIn('language_id',$userLanguage)->where('album_name', 'LIKE', '%'.$name.'%')->get();
            $data['audios'] = Audio::whereIn('audio_language',$userLanguage)->where('audio_title', 'LIKE', '%'.$name.'%')->get();
        }else{
            $data['albums'] = Album::where('album_name', 'LIKE', '%'.$name.'%')->get();
            $data['audios'] = Audio::where('audio_title', 'LIKE', '%'.$name.'%')->get();
        }
        
        $data['ytBrowseSearch'] = $ytBrowseSearch;       

        $data['artistData'] = Artist::where('artist_name', 'LIKE', '%'.$name.'%')->get();
        if($request->ajax()){     
            $html = view('ajax.search',$data)->render();
            $response['status'] = true;
            $response['html'] = $html;
            return response()->json($response);
        }else{
            return view('front.search', $data)->render();
        }
        
    }

    public function faq(){
        $data['faqs'] = Faq::where('status' , 1)->get();
        return view('front.faq', $data);
    }

    public function blog(Request $request){
        $data['blogCategories'] = BlogCategories::where('is_active',1)->get()->toArray();
        $data['blogs'] = Blogs::where('is_active', 1)->get();
        if($request->ajax()){     
            $html = view('ajax.blog',$data)->render();
            $response['status'] = true;
            $response['html'] = $html;
            return response()->json($response);
        }else{
            return view('front.blog', $data)->render();
        }
    }

    public function pages($id){
        $pageId = Crypt::decrypt($id);
        $data['pageData'] = Pages::find($pageId);
        if(!empty($data['pageData'])){
            return view('front.pages', $data);
        }else{
            redirect('home');
        }
    }

    public function readNotification(){
        $user = User::find(Auth::user()->id);
        $user->unreadNotifications->markAsRead();
        echo json_encode(['status' => 1]);
    }
    
    public function mark_read_notification($id = null){
        
        $date = date('Y-m-d h:i:s');
        $update = Notification::where('id',$id)->update(['read_at'=>$date]);
        if($update){
            $resp = ['status' => 1, 'msg' => __('frontWords.update_success')];
        }else{
            $resp = ['status' => 0, 'msg' => __('frontWords.something_wrong')];
        }
        return response()->json($resp);
    }
    
    public function remove_notification($id = null){
        $update = Notification::where('id',$id)->update(['remove_it'=>'1']);
        if($update){
            $resp = ['status' => 1, 'msg' => __('frontWords.update_success')];
        }else{
            $resp = ['status' => 0, 'msg' => __('frontWords.something_wrong')];
        }
        return response()->json($resp);
    }
    
    public function remove_all_notification($id = null){
        $update = Notification::where('notifiable_id',Auth::user()->id)->update(['remove_it'=>'1']);
        if($update){
            $resp = ['status' => 1, 'msg' => __('frontWords.update_success')];
        }else{
            $resp = ['status' => 0, 'msg' => __('frontWords.something_wrong')];
        }
        return response()->json($resp);
    }
    
    public function clear_all_history(){
        
        $userHistory = DB::table('user_history')->where('user_id', Auth::user()->id)->delete();
        
        if(!empty($userHistory)){
            $resp = ['status'=> 1, 'msg'=> __('frontWords.history').' '.__('frontWords.remove_success') ];
        }else{
            $resp = ['status'=>0, 'msg'=>__('frontWords.something_wrong')];
        }
        
        return response()->json($resp);
    }
    
    public function getAdminAddedPlaylist($playlistData = null){
        $playlist_audios = [];
        if($playlistData != '' && sizeof($playlistData) > 0){
            $albumUniqueIds = [];
            $allArtistIds = [];
            $finalAudioIds = [];
            $allAudioIds = [];
            $audioIds = [];
            foreach($playlistData as $playlist){

                if(!empty($playlist['album_id'])){                
                        $albumId = json_decode($playlist['album_id']);                            
                        $albumsMusicId = [];                            
                        $allAlbumIds = [];
                        $musicIds = [];

                        foreach($albumId as $id){
                            $albumsMusicId[] = Album::select('song_list')->where('id', $id)->first();
                        }
                        foreach($albumsMusicId as $musicId){
                            $musicIds[] = json_decode($musicId->song_list);
                        }
                        
                        foreach($musicIds as $ids){
                            foreach($ids as $id){
                                $allAlbumIds[] += $id; 
                            }
                        }
                        $albumUniqueIds = array_unique($allAlbumIds);
                }

                if(!empty($playlist['artist_id'])){
                    $artistMusicId = Audio::select('id')->where('artist_id',$playlist['artist_id'])->get()->toArray();
                    foreach($artistMusicId as $musicIds){
                        foreach($musicIds as $id){
                            $allArtistIds[] += $id; 
                        }
                    }
                }

                if(!empty($playlist['audio_id'])){
                    foreach(json_decode($playlist['audio_id']) as $id){                                 
                        $allAudioIds[] += $id; 
                    }                   
                }

                $finalAudioIds += array_merge($albumUniqueIds, $allArtistIds,$allAudioIds);
                if(!empty($finalAudioIds)){

                    $audioIds = array_unique($finalAudioIds);
                    $audioIds['playlist_title'] = $playlist['playlist_title'];
                    $audioIds['playlist_audio'] = Audio::whereIn('id',$audioIds)->orderBy('id','desc')->get();
                    $playlist_audios[] = ['playlist_title' => $audioIds['playlist_title'], 'playlist_audio' => $audioIds['playlist_audio']];
                }
                                   
            }
        }
        return $playlist_audios;
    }   
    
    // Get User Favourite Songs
    public function getUserFavouriteSongs($favourites = null){
        $favouriteAudioIds = [];
        if(sizeof($favourites) > 0){
            $finalAudioIds = [];
            $albumUniqueIds = [];
            $artistUniqueIds = [];
            $playlistUniqueIds = [];
            $genreUniqueIds = [];
            $audioIds = [];
            $allAlbumAudioIds = [];
            $allArtistAudioIds = [];
            $allPlaylistAudioIds = [];
            $genreMusicIds = [];
            if($favourites[0]->album_id != ''){
                $album = json_decode($favourites[0]->album_id);
                $albumsMusicId = Album::select('song_list')->whereIn('id', $album)->get();
                foreach($albumsMusicId as $musicId){ $albumMusicIds[] = json_decode($musicId->song_list); }
                foreach($albumMusicIds as $ids){ if(!empty($ids)){ foreach($ids as $id){ $allAlbumAudioIds[] += $id; } } }
                $albumUniqueIds = array_unique($allAlbumAudioIds);
                
            }
            if($favourites[0]->artist_id != ''){
                $artistIds = json_decode($favourites[0]->artist_id);
                if(!empty($artistIds)){
                    $artistMusicId = AudioArtist::select('audio_id')->whereIn('artist_id',$artistIds)->get()->toArray();
                    foreach($artistMusicId as $musicId){ $artistMusicIds[] = json_decode($musicId['audio_id']); }
                    foreach($artistMusicIds as $ids){ if(!empty($ids)){ foreach($ids as $id){ $allArtistAudioIds[] += $id; } } }
                    $artistUniqueIds = array_unique($allArtistAudioIds);
                }
            }
            if($favourites[0]->playlist_id != '' && $favourites[0]->playlist_id != '[]'){
                $playlistIds = json_decode($favourites[0]->playlist_id);
                $playlistMusicId = Playlist::select('song_list')->whereIn('id',$playlistIds)->get()->toArray();
                foreach($playlistMusicId as $musicId){ $playlistMusicIds[] = json_decode($musicId['song_list']); }
                foreach($playlistMusicIds as $ids){ if(!empty($ids)){ foreach($ids as $id){ $allPlaylistAudioIds[] += $id; } } }
                $playlistUniqueIds = array_unique($allPlaylistAudioIds);
            }
            if($favourites[0]->genre_id != ''){
                $genreIds = json_decode($favourites[0]->genre_id);
                $genreMusicId = Audio::select('id')->whereIn('audio_genre_id',$genreIds)->get()->toArray();
                foreach($genreMusicId as $musicId){ $genreMusicIds[] = json_decode($musicId['id']); }
                $genreUniqueIds = array_unique($genreMusicIds);
            }
            if($favourites[0]->audio_id != ''){ $audioIds = json_decode($favourites[0]->audio_id); }
            $finalAudioIds = array_merge($albumUniqueIds, $artistUniqueIds,$playlistUniqueIds,$genreUniqueIds,$audioIds);
            if(!empty($finalAudioIds)){
                $favouriteAudioIds = array_unique($finalAudioIds);
            }
        }
        return $favouriteAudioIds;
    }
}
