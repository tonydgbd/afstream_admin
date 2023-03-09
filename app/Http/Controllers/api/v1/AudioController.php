<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Modules\AdminPlaylist\Entities\AdminPlaylist;
use Modules\AudioLanguage\Entities\AudioLanguage;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Modules\Setting\Entities\Settings;
use Modules\Audio\Entities\AudioGenre;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Artist\Entities\Artist;
use Alaouy\Youtube\Facades\Youtube;
use Modules\Coupon\Entities\Coupon;
use Modules\Audio\Entities\Audio;
use Modules\Album\Entities\Album;
use Modules\Plan\Entities\Plan;
use Illuminate\Http\Request;
use App\UserHistory;
use App\UserAction;
use App\AppVersion;
use App\Favourite;
use App\Playlist;
use App\User;
use stdClass;
use DB;


class AudioController extends Controller
{
    
    public $successStatus = true;
    public $errorStatus = false;
    public $errorMsg = 'Something went wrong.';

    
    
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
    
    function checkFavouriteMusic($param){
        $favIds = Favourite::where('user_id',auth()->user()->id)->select('audio_id')->first();
        if(!empty($favIds->audio_id)){
            $favIds = json_decode($favIds->audio_id); 
            $key = in_array($param['id'], $favIds);               
            if(!empty($key)){
                return '1';
            }else{
                return '0';
            }

        }return '0';
    }

    function getSongAWSUrl($dataArr){
        
        $SrcDirectorty = env('AWS_DIRECTORY'); 
        $url = 'https://'.env('AWS_BUCKET').'.s3.amazonaws.com/'.$SrcDirectorty;
        $files = Storage::disk('s3')->files($SrcDirectorty); 
        foreach ($files as $file) {
            if(str_replace($SrcDirectorty.'/', '', $file) == $dataArr){
                return $url.'/'.$dataArr;
            }
        }
    }
    

    function getSongArrayForm($songs){
        
        foreach($songs as $audio){      
            $audioUrl = '';
            if($audio['aws_upload']){
                $audioUrl = $this->getSongAWSUrl($audio['audio']);
            }else{
                $audioUrl = url('').'/'.'images/audio/'.$audio['audio'];
            }  
            
            $artists_name = $this->getArtistName(['artist_id' => $audio['artist_id']]);
            $favourite = $this->checkFavouriteMusic(['id' => $audio['id']]);
            $songArr[] = [
                'id' => $audio['id'],
                'image' => $audio['image'],
                'audio' =>  $audioUrl,
                'audio_duration' => $audio['audio_duration'],
                'audio_title' => $audio['audio_title'],
                'audio_slug' => $audio['audio_slug'],
                'download_price' => $audio['download_price'],
                'audio_genre_id' => $audio['audio_genre_id'],
                'artist_id' => $audio['artist_id'],
                'artists_name' => $artists_name,
                'favourite' => $favourite,
                'audio_language' => $audio['audio_language'],
                'listening_count' => $audio['listening_count'],
                'is_featured' => $audio['is_featured'],
                'is_trending' => $audio['is_trending'],
                'is_recommended' => $audio['is_recommended'],
                'created_at' => $audio['created_at'],
            ];
            
        }
        return $songArr;
    }
    
    
    public function getUserLang(){

        if(isset(Auth::user()->id)){
            $language = Favourite::where('user_id', Auth::user()->id)->get();
            $setLanguage = [];
            if(sizeof($language) > 0){
                $setLanguage = json_decode($language[0]->user_language);
            }
        }
        return $setLanguage;
    }

    /**
     * Display a listing of the Music With Language ,Genre And Artist.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMusiclanguages(Request $request) 
    {
        if($request->isMethod('get')){
            
            $user = Auth::user();

            if(!empty($user)){
                $languages = [];
                $languagess = select(['table'=>'audio_languages','column'=>'*','order'=>['id','desc'], 'where'=>['status'=> '1']]);
                $setLang = $this->getUserLang();
                foreach($languagess as $language){
                    if(empty($language->image)){
                        $language->image = '';
                    }
                    $languages[] = $language;
                }
                if(!empty($languages)){
                    $response['status'] = $this->successStatus;
                    $response['msg'] = "Music language successfully found.";
                    $response['imagePath'] =  'images/language/';
                    $response['data'] =  $languages;
                    if($setLang == 'null' || $setLang == ''){
                        $response['selectedLanguage'] = [];
                    }else{
                        $response['selectedLanguage'] = $setLang;
                    }
                }else{
                    $response['status'] = $this->errorStatus;
                    $response['msg'] = 'Sorry no music language found.';
                    $response['data'] =  [];
                    $response['selectedLanguage'] =  [];
                }
            }else{
                $response['status'] = $this->errorStatus;
                $response['msg'] = 'Unauthenticated.';
                $response['data'] =  new stdClass();
            }
            return response()->json($response);
        }
    }

    /**
     * Display a listing of the Music By Language Ids.
     *
     * @return \Illuminate\Http\Response
     */
    public function setMusicLanguages(Request $request) 
    {
        if($request->isMethod('post')){

            $user = Auth::user();            
            if(!empty($user)){               
               
                $languageIds =  $request->language_id;  
                
                if(isset($languageIds) && !empty($languageIds)){                    
                    
                    $checkLanguage = $this->getUserLang();
                    
                    if(!empty($checkLanguage)){
                        $emptyLanguage =  DB::table('favourites')->where(['user_id' => Auth::user()->id])->update(['user_language' => '']);
                        $saveLanguage =  DB::table('favourites')->where(['user_id' => Auth::user()->id])->update(['user_language' => $languageIds]);
                        
                    }else{
                        
                        $checkDetail = Favourite::where('user_id' , Auth::user()->id)->first();
                        if(empty($checkDetail)){
                            $audioLanguage = new Favourite();
                            $audioLanguage->user_id = Auth::user()->id;
                            $audioLanguage->user_language = $languageIds;
                            $saveLanguage = $audioLanguage->save();
                        }else{
                            $emptyLanguage =  DB::table('favourites')->where(['user_id' => Auth::user()->id])->update(['user_language' => '']);
                            $saveLanguage =  DB::table('favourites')->where(['user_id' => Auth::user()->id])->update(['user_language' => $languageIds]);
                        }
                    }

                    if($saveLanguage){                    
                        $response['status'] = $this->successStatus;
                        $response['msg'] = "Music Language successfully saved.";
                        $response['data'] =  $languageIds;
                        
                    }else{
                        $response['status'] = $this->errorStatus;
                        $response['msg'] = $this->errorMsg;
                        $response['data'] =  new stdClass();
                    }                   

                }else{
                    $response['status'] = $this->errorStatus;
                    $response['msg'] = "Language id is required.";
                    $response['data'] =  new stdClass();
                }

            }else{
                $response['status'] = $this->errorStatus;
                $response['msg'] = 'Unauthenticated.';
                $response['data'] =  new stdClass();
            }
            return response()->json($response);
        }
    }
    

    /**
     * Get All Music Categories
     *
     * @return \Illuminate\Http\Response
     */
    public function getMusicCategories(Request $request) 
    {
        if($request->isMethod('get')){
            $user = Auth::user();
            
            if(!empty($user)){
                $audioGenre = array();
                $trendingAudioGenre = array();
                $artist = array();
                $featuredArtist = array();
                $album = array();
                $featuredAlbum = array();
                $trending = array();
                $featured = array();
                $recommended = array();
                $playlistData = array();                

                $setLang = $this->getUserLang();
                
                    $trending['cat_name'] = 'Trending Songs';
                    $trending['sub_category'] = Audio::select('id','audio_title as name','audio_slug as slug','image','is_featured','is_trending','is_recommended')->whereIn('audio_language', $setLang)->where(['status'=>'1','is_trending'=>'1'])->skip(0)->take(5)->get();
                    $trending['imagePath'] =  'images/audio/thumb/';
                
                    $featured['cat_name'] = 'Featured Songs';
                    $featured['sub_category'] = Audio::select('id','audio_title as name','audio_slug as slug','image','is_featured','is_trending','is_recommended')->whereIn('audio_language', $setLang)->where(['status'=>'1','is_featured'=>'1'])->skip(0)->take(5)->get();
                    $featured['imagePath'] =  'images/audio/thumb/';
                
                    $recommended['cat_name'] = 'Recommended Songs';
                    $recommended['sub_category'] = Audio::select('id','audio_title as name','audio_slug as slug','image','is_featured','is_trending','is_recommended')->whereIn('audio_language', $setLang)->where(['status'=>'1','is_recommended'=>'1'])->skip(0)->take(5)->get();
                    $recommended['imagePath'] =  'images/audio/thumb/';
                
                    $audioGenre['cat_name'] = 'Genres'; //AudioGenre::where('status','1')->get();
                    $audioGenre['sub_category'] = AudioGenre::select('id','genre_name as name','genre_slug as slug','image','is_featured','is_trending','is_recommended')->where('status','1')->skip(0)->take(5)->get();
                    $audioGenre['imagePath'] =  'images/audio/audio_genre/';                        
                    
                    $trendingAudioGenre['cat_name'] = 'Trending Genres';
                    $trendingAudioGenre['sub_category'] = AudioGenre::select('id','genre_name as name','genre_slug as slug','image','is_featured','is_trending','is_recommended')->where(['status'=>'1' , 'is_trending'=> '1'])->skip(0)->take(5)->get();
                    $trendingAudioGenre['imagePath'] =  'images/audio/audio_genre/';                   
                
                    $artist['cat_name'] = 'Artists';
                    $artist['sub_category'] = Artist::select('id','artist_name as name','artist_slug as slug','image','is_featured','is_trending','is_recommended')->where('status','1')->orderBy('id','desc')->skip(0)->take(5)->get();
                    $artist['imagePath'] =  'images/artist/';

                    $featuredArtist['cat_name'] = 'Featured Artists';
                    $featuredArtist['sub_category'] = Artist::select('id','artist_name as name','artist_slug as slug','image','is_featured','is_trending','is_recommended')->where(['is_featured'=>'1' , 'status'=>'1'])->orderBy('id','desc')->skip(0)->take(5)->get();
                    $featuredArtist['imagePath'] =  'images/artist/';
                
                    $album['cat_name'] = 'Albums';
                    $album['sub_category'] = Album::select('id','album_name as name','album_slug as slug','image','is_featured','is_trending','is_recommended')->whereIn('language_id', $setLang)->where(['status' => '1'])->whereIn('language_id', $setLang)->orderBy('id','desc')->skip(0)->take(5)->get();
                    $album['imagePath'] =  'images/album/';

                    $featuredAlbum['cat_name'] = 'Featured Albums';
                    $featuredAlbum['sub_category'] = Album::select('id','album_name as name','album_slug as slug','image','is_featured','is_trending','is_recommended')->whereIn('language_id', $setLang)->where(['is_featured' => '1' , 'status' => '1'])->whereIn('language_id', $setLang)->orderBy('id','desc')->skip(0)->take(5)->get();
                    $featuredAlbum['imagePath'] =  'images/album/';

					$playlistData = AdminPlaylist::where('status','1')->whereIn('audio_language', $setLang)->orderBy('id','desc')->get();
                   
					 
					 
					$playlist_audios =array();
                    
                   
						
                        foreach($playlistData as $key => $value){
					      $audio                    = array();
						
						  $audio['cat_name'] = $value['playlist_title'];
						   
						  $audio_id                   = json_decode($value['audio_id']);
						 
						  $audio['sub_category'] = Audio::select('id','audio_title as name','audio_slug as slug','image','is_featured','is_trending','is_recommended')->whereIn('id',$audio_id)->orderBy('id','desc')->take(5)->get();
						  $audio['imagePath'] =  'images/audio/thumb/';
						  $playlist_audios[] = $audio;

                    
                            

                        }
				
                    
                  

                $staticDataArray = array($audioGenre,$trendingAudioGenre,$artist,$featuredArtist,$album,$featuredAlbum,$trending,$featured,$recommended);

                $mainData = array_merge($playlist_audios,$staticDataArray);
                
                if(sizeof($trending['sub_category']) > 0 || sizeof($featured['sub_category']) > 0 || sizeof($recommended['sub_category']) > 0 || sizeof($audioGenre['sub_category']) > 0 || sizeof($trendingAudioGenre['sub_category']) > 0 || 
                sizeof($artist['sub_category']) > 0 || sizeof($featuredArtist['sub_category']) > 0 || sizeof($album['sub_category']) > 0 || sizeof($featuredAlbum['sub_category']) > 0 || sizeof($playlist_audios) > 0){
                    
                    $response['status'] = $this->successStatus;
                    $response['msg'] = "Music categories successfully found.";
                    $response['data'] =  $mainData;
                }else{
                    $response['status'] = $this->errorStatus;
                    $response['msg'] = 'Sorry no category found.';
                    $response['data'] =  [];
                }
                
            }else{
                $response['status'] = $this->errorStatus;
                $response['msg'] = 'Unauthenticated.';
                $response['data'] =  [];
            }
            return response()->json($response);
        }
    }


    /**
     * Get Music By Categories Id
     *
     * @return \Illuminate\Http\Response
     */
     public function getMusicByCategoryId(Request $request) 
    {
        if($request->isMethod('post')){
            $user = Auth::user();

            if(!empty($user)){
            
                $songs = [];                           
                $type = $request->type;
				
                $id = $request->id;  
              
                
                if(!empty($type) && !empty($id)){      
                    $userLanguage = $this->getUserLang();   
                    
                    if($type == 'audio'){
                        if(!empty($userLanguage)){
                            $songs = Audio::select('*')->whereIn('audio_language',$userLanguage)->where(['id'=> $id,'status' => '1'])->get()->toArray();
                        }else{
                            $songs = Audio::select('*')->where(['id'=> $id,'status' => '1'])->get()->toArray();
                        }
                        
                    }elseif($type == 'Genres'){
                        //if(!empty($userLanguage)){
                            //$songs = Audio::select('*')->whereIn('audio_language',$userLanguage)->where(['audio_genre_id'=> $id,'status' => '1'])->get()->toArray();
                        //}else{
                            $songs = Audio::select('*')->where(['audio_genre_id'=> $id,'status' => '1'])->get()->toArray();
                        //}
                        
                    }elseif($type == 'Trending Genres'){
                        $songs = Audio::select('*')->where(['audio_genre_id'=> $id,'status' => '1','is_trending'=>'1'])->get()->toArray();
                        
                    }elseif($type == 'Artists'){
                        $songs = Audio::select('*')->whereJsonContains('artist_id', $id)->where(['status' => '1'])->get()->toArray();
                        
                    }elseif($type == 'Featured Artists'){
                            $songs = Audio::select('*')->whereJsonContains('artist_id', $id)->where(['status' => '1','is_featured'=>'1'])->get()->toArray();
                        
                    }elseif($type == 'Trending Songs'){
                        $songs = Audio::select('*')->where(['id'=> $id,'status' => '1','is_trending'=>'1'])->get()->toArray();
                        
                    }elseif($type == 'Featured Songs'){
                        $songs = Audio::select('*')->where(['id'=> $id,'status' => '1','is_featured'=>'1'])->get()->toArray();
                        
                    }elseif($type == 'Recommended Songs'){
                        
						$songs = Audio::select('*')->where(['id'=> $id,'status' => '1','is_recommended'=>'1'])->get()->toArray();
						 
                        
					
                    }elseif($type == 'Albums'){
                        if(!empty($userLanguage)){
							
                            $songjsonIds = Album::select('song_list')->whereIn('language_id' , $userLanguage)->where(['id'=>$id,'status'=>'1'])->first();
                         
						}else
						{
                            $songjsonIds = Album::select('song_list')->where(['id'=>$id,'status'=>'1'])->first();
                        }
                        if(!empty($songjsonIds)){
                            $songIds =json_decode($songjsonIds['song_list']);
                            $songs = Audio::select('*')->whereIn('audio_language',$userLanguage)->whereIn('id',$songIds)->where(['status' => '1'])->get()->toArray();
                         
						}
						
                        
                    }elseif($type == 'Featured Albums'){      
                        if(!empty($userLanguage)){
                            $songjsonIds = Album::select('song_list')->whereIn('language_id' , $userLanguage)->where(['id'=>$id,'status'=>'1','is_featured'=>'1'])->first();
                        }else{
                            $songjsonIds = Album::select('song_list')->where(['id'=>$id,'status'=>'1','is_featured'=>'1'])->first();
                        }
                        if(!empty($songjsonIds)){
                            $songIds =json_decode($songjsonIds['song_list']);
                            $songs = Audio::select('*')->whereIn('audio_language',$userLanguage)->whereIn('id',$songIds)->where(['status' => '1'])->get()->toArray();                            
                        }
                    }
					
					
					else{
                        $playlistData = AdminPlaylist::select('audio_id')->where('playlist_title',$type)->orderBy('audio_id', 'ASC')->first();
                       
					
					   if(!empty($playlistData) && $playlistData != ''){
                            if(!empty($userLanguage)){
                                $songs = Audio::select('*')->whereIn('audio_language',$userLanguage)->where(['id'=> $id,'status' => '1'])->first();
                            
							}else{
                                $songs = Audio::select('*')->where(['id'=> $id,'status' => '1'])->first();
                            
							}
							if(!empty($playlistData)){
								
                            $songIds =json_decode($playlistData['audio_id']);
							$songs = Audio::select('*')->whereIn('audio_language',$userLanguage)->whereIn('id',$songIds)->where(['status' => '1'])->get()->toArray();                            
                           //$new_arr =array_reverse($songs);
						}
							
						
							
                        }
                    }
					
                    
                    $songArr = [];
                    if(sizeof($songs) > 0){
                        $songArr = $this->getSongArrayForm($songs);
						 
						
                    }
                    if($songArr){
						
                           $key = array_search($id, array_column($songArr,  'id'));
						   $t = $songArr[$key];
                           unset($songArr[$key]);
                           array_unshift($songArr, $t);
						
                        $response['status'] = $this->successStatus;
                        $response['msg'] = "Music successfully found.";
                        $response['data'] =  $songArr;
						  
					}else{
						
                        $response['status'] = $this->errorStatus;
                        $response['msg'] = "Sorry no music found.";
                        $response['data'] =  [];
                    }

                }else{
                    $response['status'] = $this->errorStatus;
                    $response['msg'] = "Category type and id is required.";
                    $response['data'] =  [];
                }
            }else{
				
                $response['status'] = $this->errorStatus;
                $response['msg'] = 'Unauthenticated.';
                $response['data'] =  [];
            }
            $response['imagePath'] =  'images/audio/thumb/';
            $response['audioPath'] =  'images/audio/';
            return response()->json($response);
        }
    }
  
  
    /**
     * Get Music By all
     *
     * @return \Illuminate\Http\Response
     */
     
   
     
  
    public function getMusicAll(Request $request)
    {
       
         
        if($request->isMethod('post')){
            $user = Auth::user();
        
       
            if(!empty($user)){
              
                
                //$songs = [];                           
                $type = $request->type;
               
                $page =$request->page;
                
                $limit =$request->limit;
                
                
                
                
               
                if(!empty($type)){
                 
                //$userLanguage = $this->getUserLang();
                $user = Auth::user();
                if(!empty($user)){
                $audioGenre = array();
                $trendingAudioGenre = array();
                $artist = array();
                $featuredArtist = array();
                $album = array();
                $featuredAlbum = array();
                $trending = array();
                $featured = array();
                $recommended = array();
                $playlistData = array();                

                $setLang = $this->getUserLang();
               
                
                    if($request->type == 'Trending Songs'){
                         $trending['type'] = 'Trending Songs';
                         $trending['status'] = $this->successStatus;
                         $trending['msg'] = "Music categories successfully found.";
                         
                         $page = $request->has('page') ? $request->get('page') : 1;
                         $limit = $request->has('limit') ? $request->get('limit') : 10;
                       
                         
                         $trending['sub_category'] = Audio::select('id','audio_title as name','audio_slug as slug','image','is_featured','is_trending','is_recommended')->whereIn('audio_language', $setLang)->where(['status'=>'1','is_trending'=>'1'])->limit($limit)->offset(($page - 1) * $limit)->get();
                        $trending['imagePath'] =  'images/audio/thumb/';
                        return $trending;
                      //  return response->json(trending['sub_category']);
                    
                    
                        
                    }
                    if($request->type == 'Featured Songs'){
                         $featured['type'] = 'Featured Songs';
                         $featured['status'] = $this->successStatus;
                         $featured['msg'] = "Music categories successfully found.";
                         
                         $page = $request->has('page') ? $request->get('page') : 1;
                         $limit = $request->has('limit') ? $request->get('limit') : 10;
                         
                         $featured['sub_category'] = Audio::select('id','audio_title as name','audio_slug as slug','image','is_featured','is_trending','is_recommended')->whereIn('audio_language', $setLang)->where(['status'=>'1','is_featured'=>'1'])->limit($limit)->offset(($page - 1) * $limit)->get();
                         $featured['imagePath'] =  'images/audio/thumb/';
                         return $featured;
                        
                    }
                    
                
                    if($request->type == 'Recommended Songs'){
                          $recommended['status'] = $this->successStatus;
                          $recommended['msg'] = "Music categories successfully found.";
                          $recommended['type'] = 'Recommended Songs';
                          $page = $request->has('page') ? $request->get('page') : 1;
                          $limit = $request->has('limit') ? $request->get('limit') : 10;
                    
                          $recommended['sub_category'] = Audio::select('id','audio_title as name','audio_slug as slug','image','is_featured','is_trending','is_recommended')->whereIn('audio_language', $setLang)->where(['status'=>'1','is_recommended'=>'1'])->limit($limit)->offset(($page - 1) * $limit)->get();
                          $recommended['imagePath'] =  'images/audio/thumb/';
                          return $recommended;
                    
                    }
                    if($request->type=='Genres'){
                    $audioGenre['type'] = 'Genres'; //AudioGenre::where('status','1')->get();
                   
                    $audioGenre['status'] = $this->successStatus;
                    $audioGenre['msg'] = "Music categories successfully found.";
                   
                    $page = $request->has('page') ? $request->get('page') : 1;
                    $limit = $request->has('limit') ? $request->get('limit') : 10;
                   
                   
                    $audioGenre['sub_category'] = AudioGenre::select('id','genre_name as name','genre_slug as slug','image','is_featured','is_trending','is_recommended')->where('status','1')->limit($limit)->offset(($page - 1) * $limit)->get();
                    $audioGenre['imagePath'] =  'images/audio/audio_genre/'; 
                  return $audioGenre;
                   }
                    //ciel
                    //$audioGenre['imagePath'] =  'images/audio/audio_genre/';                        
                      if($request->type=='Trending Genres'){
                      $trendingAudioGenre['type'] = 'Trending Genres';
                      $trendingAudioGenre['status'] = $this->successStatus;
                      $trendingAudioGenre['msg'] = "Music categories successfully found.";
                    
                      $page = $request->has('page') ? $request->get('page') : 1;
                      $limit = $request->has('limit') ? $request->get('limit') : 10;
                      $trendingAudioGenre['sub_category'] = AudioGenre::select('id','genre_name as name','genre_slug as slug','image','is_featured','is_trending','is_recommended')->where(['status'=>'1' , 'is_trending'=> '1'])->limit($limit)->offset(($page - 1) * $limit)->get();
                     $trendingAudioGenre['imagePath'] =  'images/audio/audio_genre/';
                      return $trendingAudioGenre;
                      }
                    //                   
                    
                   if($request->type=='Artists'){
                         $artist['type'] = 'Artists';
                        $artist['status'] = $this->successStatus;
                        $artist['msg'] = "Music categories successfully found.";
                      
                        $page = $request->has('page') ? $request->get('page') : 1;
                        $limit = $request->has('limit') ? $request->get('limit') : 10; 
                        
                        $artist['sub_category'] = Artist::select('id','artist_name as name','artist_slug as slug','image','is_featured','is_trending','is_recommended')->where('status','1')->orderBy('id','desc')->limit($limit)->offset(($page - 1) * $limit)->get();
                   
                       $artist['imagePath'] =  'images/artist/';
                       return $artist;
                      
                       
                   }
                    //
                     if($request->type=='Featured Artists'){
                         $featuredArtist['type'] = 'Featured Artists';
                         $featuredArtist['status'] = $this->successStatus;
                         $featuredArtist['msg'] = "Music categories successfully found.";
                         
                         $page = $request->has('page') ? $request->get('page') : 1;
                         $limit = $request->has('limit') ? $request->get('limit') : 10;
                        
                         $featuredArtist['sub_category'] = Artist::select('id','artist_name as name','artist_slug as slug','image','is_featured','is_trending','is_recommended')->where(['is_featured'=>'1' , 'status'=>'1'])->orderBy('id','desc')->limit($limit)->offset(($page - 1) * $limit)->get();
                         $featuredArtist['imagePath'] =  'images/artist/';
                        return $featuredArtist;
                         
                     }
                    
                    if($request->type=='Albums'){
                        $album['type']= 'Albums';
                    
                         $album['status'] = $this->successStatus;
                         $album['msg'] = "Music categories successfully found.";
                         
                         $page = $request->has('page') ? $request->get('page') : 1;
                         $limit = $request->has('limit') ? $request->get('limit') : 10;
                         
                         $album['sub_category'] = Album::select('id','album_name as name','album_slug as slug','image','is_featured','is_trending','is_recommended')->whereIn('language_id', $setLang)->where(['status' => '1'])->whereIn('language_id', $setLang)->orderBy('id','desc')->limit($limit)->offset(($page - 1) * $limit)->get();
                         $album['imagePath'] =  'images/album/';
                         return $album;
                        
                    }
                    // 
                      
                        
                    
                    
                    if($request->type == 'Featured Albums'){
                       
                         $featuredAlbum['type']='Featured Albums';
                         $featuredAlbum['status'] = $this->successStatus;
                         $featuredAlbum['msg'] = "Music categories successfully found.";
                         
                         $page = $request->has('page') ? $request->get('page') : 1;
                         $limit = $request->has('limit') ? $request->get('limit') : 10;
                         
                         $featuredAlbum['sub_category'] = Album::select('id','album_name as name','album_slug as slug','image','is_featured','is_trending','is_recommended')->whereIn('language_id', $setLang)->where(['is_featured' => '1' , 'status' => '1'])->whereIn('language_id', $setLang)->orderBy('id','desc')->limit($limit)->offset(($page - 1) * $limit)->get();
                         $featuredAlbum['imagePath'] =  'images/album/';
                         return $featuredAlbum;
                    
                    }

					$playlistData = AdminPlaylist::where('status','1')->whereIn('audio_language', $setLang)->orderBy('id','desc')->get();
                 	 
					 
					$playlist_audios =array();
                   
                        foreach($playlistData as $key => $value){
					      $audio                    = array();
						
						if($request->type == $value['playlist_title']){
						   $audio['type']=$value['playlist_title'];
						    $audio['status'] = $this->successStatus;
                            $audio['msg'] = "Music categories successfully found.";
						  
						    $page = $request->has('page') ? $request->get('page') : 1;
                            $limit = $request->has('limit') ? $request->get('limit') : 10;
						  
						  
						   $audio_id                   = json_decode($value['audio_id']);
						 
						  $audio['sub_category'] = Audio::select('id','audio_title as name','audio_slug as slug','image','is_featured','is_trending','is_recommended')->whereIn('id',$audio_id)->orderBy('id','desc')->limit($limit)->offset(($page - 1) * $limit)->get();
					      $audio['imagePath'] =  'images/audio/thumb/';
					      return $audio;
						  
						}
						  $playlist_audios[] = $audio;
						  

                    }
				
           
                $staticDataArray = array($audioGenre,$trendingAudioGenre,$artist,$featuredArtist,$album,$featuredAlbum,$trending,$featured,$recommended);
              
              
               
                $mainData = array_merge($playlist_audios,$staticDataArray);
                
                if(sizeof($trending['sub_category']) > 0 || sizeof($featured['sub_category']) > 0 || sizeof($recommended['sub_category']) > 0 || sizeof($audioGenre['sub_category']) > 0 || sizeof($trendingAudioGenre['sub_category']) > 0 || 
                sizeof($artist['sub_category']) > 0 || sizeof($featuredArtist['sub_category']) > 0 || sizeof($album['sub_category']) > 0 || sizeof($featuredAlbum['sub_category']) > 0 || sizeof($playlist_audios) > 0){
                    
                     $key = array_search($type, array_column($mainData,  'type'));
                           
						  $t = $mainData[$key];
                         
                          unset($mainData[$key]);
                          array_unshift($mainData, $t);
                    
                  
                    
                    return $mainData;
                
                    
                }else{
                    $response['status'] = $this->errorStatus;
                    $response['msg'] = 'Sorry no category found.';
                  $response['data'] =  [];
                }
                
            }
                    
                }else
                {
                    
                  $user = Auth::user();
            
                if(!empty($user)){
               
                $audioGenre = array();
                $trendingAudioGenre = array();
                $artist = array();
                $featuredArtist = array();
                $album = array();
                $featuredAlbum = array();
                $trending = array();
                $featured = array();
                $recommended = array();
                $playlistData = array();                

                $setLang = $this->getUserLang();
                    $trending['status'] = $this->successStatus;
                    $trending['msg'] = "Music categories successfully found.";
                    $trending['cat_name'] = 'Trending Songs';
                    $trending['sub_category'] = Audio::select('id','audio_title as name','audio_slug as slug','image','is_featured','is_trending','is_recommended')->whereIn('audio_language', $setLang)->where(['status'=>'1','is_trending'=>'1'])->skip(0)->take(5)->get();
                    $trending['imagePath'] =  'images/audio/thumb/';
                
                    $featured['cat_name'] = 'Featured Songs';
                    $featured['sub_category'] = Audio::select('id','audio_title as name','audio_slug as slug','image','is_featured','is_trending','is_recommended')->whereIn('audio_language', $setLang)->where(['status'=>'1','is_featured'=>'1'])->skip(0)->take(5)->get();
                    $featured['imagePath'] =  'images/audio/thumb/';
                
                    $recommended['cat_name'] = 'Recommended Songs';
                    $recommended['sub_category'] = Audio::select('id','audio_title as name','audio_slug as slug','image','is_featured','is_trending','is_recommended')->whereIn('audio_language', $setLang)->where(['status'=>'1','is_recommended'=>'1'])->skip(0)->take(5)->get();
                    $recommended['imagePath'] =  'images/audio/thumb/';
                
                    $audioGenre['cat_name'] = 'Genres'; //AudioGenre::where('status','1')->get();
                    $audioGenre['sub_category'] = AudioGenre::select('id','genre_name as name','genre_slug as slug','image','is_featured','is_trending','is_recommended')->where('status','1')->skip(0)->take(5)->get();
                    $audioGenre['imagePath'] =  'images/audio/audio_genre/';                        
                    
                    $trendingAudioGenre['cat_name'] = 'Trending Genres';
                    $trendingAudioGenre['sub_category'] = AudioGenre::select('id','genre_name as name','genre_slug as slug','image','is_featured','is_trending','is_recommended')->where(['status'=>'1' , 'is_trending'=> '1'])->skip(0)->take(5)->get();
                    $trendingAudioGenre['imagePath'] =  'images/audio/audio_genre/';                   
                
                    $artist['cat_name'] = 'Artists';
                    $artist['sub_category'] = Artist::select('id','artist_name as name','artist_slug as slug','image','is_featured','is_trending','is_recommended')->where('status','1')->orderBy('id','desc')->skip(0)->take(5)->get();
                    $artist['imagePath'] =  'images/artist/';

                    $featuredArtist['cat_name'] = 'Featured Artists';
                    $featuredArtist['sub_category'] = Artist::select('id','artist_name as name','artist_slug as slug','image','is_featured','is_trending','is_recommended')->where(['is_featured'=>'1' , 'status'=>'1'])->orderBy('id','desc')->skip(0)->take(5)->get();
                    $featuredArtist['imagePath'] =  'images/artist/';
                
                    $album['cat_name'] = 'Albums';
                    $album['sub_category'] = Album::select('id','album_name as name','album_slug as slug','image','is_featured','is_trending','is_recommended')->whereIn('language_id', $setLang)->where(['status' => '1'])->whereIn('language_id', $setLang)->orderBy('id','desc')->skip(0)->take(5)->get();
                    $album['imagePath'] =  'images/album/';

                    $featuredAlbum['cat_name'] = 'Featured Albums';
                    $featuredAlbum['sub_category'] = Album::select('id','album_name as name','album_slug as slug','image','is_featured','is_trending','is_recommended')->whereIn('language_id', $setLang)->where(['is_featured' => '1' , 'status' => '1'])->whereIn('language_id', $setLang)->orderBy('id','desc')->skip(0)->take(5)->get();
                    $featuredAlbum['imagePath'] =  'images/album/';

					$playlistData = AdminPlaylist::where('status','1')->whereIn('audio_language', $setLang)->orderBy('id','desc')->get();
                   
					 
					 
					$playlist_audios =array();
                    
                   
						
                        foreach($playlistData as $key => $value){
					      $audio                    = array();
						
						  $audio['cat_name'] = $value['playlist_title'];
						   
						  $audio_id                   = json_decode($value['audio_id']);
						 
						  $audio['sub_category'] = Audio::select('id','audio_title as name','audio_slug as slug','image','is_featured','is_trending','is_recommended')->whereIn('id',$audio_id)->orderBy('id','desc')->take(5)->get();
						  $audio['imagePath'] =  'images/audio/thumb/';
						  $playlist_audios[] = $audio;

                    
                            

                        }
				
                    
                  

                $staticDataArray = array($audioGenre,$trendingAudioGenre,$artist,$featuredArtist,$album,$featuredAlbum,$trending,$featured,$recommended);

                $mainData = array_merge($playlist_audios,$staticDataArray);
                
                if(sizeof($trending['sub_category']) > 0 || sizeof($featured['sub_category']) > 0 || sizeof($recommended['sub_category']) > 0 || sizeof($audioGenre['sub_category']) > 0 || sizeof($trendingAudioGenre['sub_category']) > 0 || 
                sizeof($artist['sub_category']) > 0 || sizeof($featuredArtist['sub_category']) > 0 || sizeof($album['sub_category']) > 0 || sizeof($featuredAlbum['sub_category']) > 0 || sizeof($playlist_audios) > 0){
                    
                   
                    $response['data'] =  $mainData;
                }else{
                    $response['status'] = $this->errorStatus;
                    $response['msg'] = 'Sorry no category found.';
                    $response['data'] =  [];
                }
                
            
                
                    
                }   
                    
                    
                    
                }
               
            }else{
                $response['status'] = $this->errorStatus;
                $response['msg'] = 'Unauthenticated.';
                $response['data'] =  [];
            }
            $response['imagePath'] =  'images/audio/thumb/';
            $response['audioPath'] =  'images/audio/';
            return response()->json($response);
        }
         
    
        
        
    }
     
     
     
     
  
    /**
     * Search Music By keyword
     *
     * @return \Illuminate\Http\Response
     */
    public function searchMusic(Request $request) 
    {
       
        if($request->isMethod('post')){
            
            $user = Auth::user();

            if(!empty($user)){
                
                $queryParams = $request->all();
                $keyword = $request->search; 
              
                $page =$request->page;
               
                $limit =$request->limit;
                
                $userLanguage = $this->getUserLang();   

                if(!empty($userLanguage) && !empty($userLanguage[0])) {
                    if(!empty($keyword)){ 
                        
                        //  $page = $request->has('page') ? $request->get('page') : 1;
                        //  $limit= $request->has('limit') ? $request->get('limit') : 10;
                         
                        $audioData = Audio::select('*')->whereIn('audio_language',$userLanguage)->where('audio_title', 'like', '%' . $keyword . '%')->where('status','1')->get()->toArray();
                       
                    }else{
                         $page  = $request->has('page') ? $request->get('page') : 1;
                         $limit = $request->has('limit') ? $request->get('limit') : 10;
                        $audioData = Audio::select('*')->whereIn('audio_language',$userLanguage)->where('status','1')->limit($limit)->offset(($page - 1) * $limit)->get()->toArray();                    
                    }
                }else{
                    if(!empty($keyword)){ 
                        $audioData = Audio::select('*')->where('audio_title', 'like', '%' . $keyword . '%')->where('status','1')->get()->toArray();
                    }else{
                        
                        
                        $audioData = Audio::select('*')->where('status','1')->get()->toArray();                    
                    }
                }
                
                if(!empty($audioData)){
                    $songArr = [];
                    if(sizeof($audioData) > 0){
                        $songArr = $this->getSongArrayForm($audioData);
                    }
                    $response['status'] = $this->successStatus;
                    $response['msg'] = "Music successfully found.";
                    $response['data'] =  $songArr;
                    
                }else{
                    $response['status'] = $this->errorStatus;
                    $response['msg'] = 'Sorry no music found.';
                    $response['data'] =  [];
                }
                
            }else{
                $response['status'] = $this->errorStatus;
                $response['msg'] = 'Unauthenticated.';
                $response['data'] =  new stdClass();
            }
            $response['imagePath'] =  'images/audio/thumb/';
            $response['audioPath'] =  'images/audio/';
            return response()->json($response);
        }
    }
    
    /**
     * Add & Remove Music To Favourite List
     *
     * @return \Illuminate\Http\Response
    */
    public function addFavouriteList(Request $request){

        if($request->isMethod('post')){
            $user = Auth::user();
            
            if(!empty($user)){
                
                $reqData = $request->all(); 
                
                if(!empty($reqData['id'])){    
                    if(isset($reqData['type']) && $reqData['type'] == 'audio'){                        
                        $id = $reqData['id'];
                        $column = 'audio_id';
                    }elseif(isset($reqData['type']) && $reqData['type'] == 'album'){                        
                        $id = $reqData['id'];
                        $column = 'album_id';
                    }elseif(isset($reqData['type']) && $reqData['type'] == 'artist'){                        
                        $id = $reqData['id'];
                        $column = 'artist_id'; 
                    }elseif(isset($reqData['type']) && $reqData['type'] == 'radio'){                        
                        $id = $reqData['id'];
                        $column = 'radio_id';
                    }else{
                        $id = $reqData['id'];
                        $column = 'audio_id';
                    }                          
                    
                    $datas = $dataId = array();
                    $userid = Auth::user()->id;
                    $datas[] = $id;
                    $getData = Favourite::where(['user_id'=> $userid])->get();
                    if(sizeof($getData) > 0){
                        if(isset($reqData['type']) && $reqData['type'] == 'audio'){
                            $decodeIds = $getData[0]->audio_id;
                        }else if(isset($reqData['type']) && $reqData['type'] == 'album'){
                            $decodeIds = $getData[0]->album_id;
                        }else if(isset($reqData['type']) && $reqData['type'] == 'artist'){
                            $decodeIds = $getData[0]->artist_id;
                        }else if(isset($reqData['type']) && $reqData['type'] == 'radio'){
                            $decodeIds = $getData[0]->radio_id;
                        }else{
                            $decodeIds = $getData[0]->audio_id;
                        }


                        if($decodeIds != '' && !empty($decodeIds)){
                            $dataId = json_decode($decodeIds);
                        }
                        
                        if( in_array($id, $dataId) ) {
                            $key = array_search($id, $dataId); 
                            unset($dataId[$key]);
                            $new_arr = array_values($dataId);
                            $update = Favourite::where('user_id', $userid)->update([$column => json_encode($new_arr)]);
                            $response = ($update) ? ['status' => $this->successStatus, 'msg' => "Removed to favourite." ] : ['status' => $this->errorStatus, 'msg' => __('frontWords.something_wrong')];
                        }else{
                            $new_arr = array_merge($dataId, $datas);
                            $create_album = Favourite::where(['user_id'=>$userid])->update([$column=>json_encode($new_arr)]);
                            $response = ($create_album) ? ['status' => $this->successStatus, 'msg' => "Added to favourite." ] : ['status' => $this->errorStatus, 'msg' => __('frontWords.something_wrong')];
                        }
                    }else{
                        $create_album = Favourite::create([$column => json_encode($datas), 'user_id'=>$userid]);
                        $response = ($create_album) ? ['status' => $this->successStatus, 'msg' => "Added to favourite."] : ['status' => $this->errorStatus, 'msg' => __('frontWords.something_wrong')];
                    }
                    
                }else{
                    $response['status'] = $this->errorStatus;
                    $response['msg'] = "Favourite music id is required.";
                    $response['data'] =  new stdClass();
                }
                
            }else{
                $response['status'] = $this->errorStatus;
                $response['msg'] = 'Unauthenticated.';
                $response['data'] =  new stdClass();
            }
            
            return response()->json($response);
        }
    }

    
    /**
     *  Favourite List
     *
     * @return \Illuminate\Http\Response
    */
        public function favouriteList(Request $request){

        if($request->isMethod('post')){
            $user = Auth::user();
            
            if(!empty($user)){
                
                $reqData = $request->all();                                       
                    
                $datas = $dataId = array();
                $userid = Auth::user()->id;
                $songs = [];
               
                $getData = Favourite::where(['user_id'=> $userid])->get();
                if(sizeof($getData) > 0){
                    if(isset($reqData['type']) && $reqData['type'] == 'audio'){
                        $decodeIds = $getData[0]->audio_id;
                    }else if(isset($reqData['type']) && $reqData['type'] == 'album'){
                        $decodeIds = $getData[0]->album_id;
                    }else if(isset($reqData['type']) && $reqData['type'] == 'artist'){
                        $decodeIds = $getData[0]->artist_id;
                    }else if(isset($reqData['type']) && $reqData['type'] == 'radio'){
                        $decodeIds = $getData[0]->radio_id;
                    }else{
                        $decodeIds = $getData[0]->audio_id;
                    }
                    
                    if($decodeIds != '' && !empty($decodeIds)){
                        $dataId = json_decode($decodeIds);
                    }
                    if(!empty($dataId)){
                        $songs = Audio::select('*')->whereIn('id',$dataId)->where(['status' => '1'])->get()->toArray();
                    }                        
                    $songArr = [];
                    if(sizeof($songs) > 0){
                        $songArr = $this->getSongArrayForm($songs);
                    }
                    if(!empty($songArr)){
                        $response['status'] = $this->successStatus;
                        $response['msg'] = "Favourite music list successfully found.";
                        $response['data'] =  $songArr;
                    }else{
                        $response['status'] = $this->errorStatus;
                        $response['msg'] = "Favourite music list is empty.";
                        $response['data'] =  [];
                    }
                }else{
                    $response['status'] = $this->errorStatus;
                    $response['msg'] = "Favourite music list is empty.";
                    $response['data'] =  [];
                }
                
            }else{
                $response['status'] = $this->errorStatus;
                $response['msg'] = 'Unauthenticated.';
                $response['data'] =  [];
            }
            $response['imagePath'] =  'images/audio/thumb/';
            $response['audioPath'] =  'images/audio/';
            return response()->json($response);
        }
    }
    
    
    public function create_playlist(Request $request){
        $user = Auth::user();
        $response['status'] = $this->errorStatus;          

        if(!empty($user)){

            $playlistName = $request->playlist_name;
            if(!empty($playlistName)){
                $playlist = Playlist::where(['playlist_name'=> $request->playlist_name,'user_id' => auth()->user()->id])->get();
                    if(count($playlist) > 0){                        
                        $response['msg'] = "Playlist name is already exist.";                        
                    }else{
                        $create = Playlist::create(['user_id'=>Auth::user()->id, 'playlist_name' => $request->playlist_name]);
                        $response['status'] = $this->successStatus;
                        $response['msg'] = "New playlist is successfully created.";                        
                    }
            }else{                    
                $response['msg'] = "Playlist name is required.";                    
            }            
        }else{            
            $response['msg'] = 'Unauthenticated.';            
        }        
        return response()->json($response);        
    }

    public function delete_playlist(Request $request){
        $user = Auth::user();
        $response['status'] = $this->errorStatus;          

        if(!empty($user)){

            $playlistId = $request->playlist_id;
            if(!empty($playlistId)){
                $playlist = Playlist::find($playlistId);
                if(!empty($playlist)){
                    $delete = $playlist->delete();
                    $response['status'] = $this->successStatus;
                    $response['msg'] = "Playlist is successfully deleted.";                        
                }else{
                    $response['msg'] = "Playlist does not found.";   
                }    
            }else{                    
                $response['msg'] = "Playlist id is required.";                    
            }            
        }else{            
            $response['msg'] = 'Unauthenticated.';            
        }        
        return response()->json($response);        
    }

    public function playlist(Request $request){
        
        if($request->isMethod('get')){
            
            $user = Auth::user();
            $response['status'] = $this->errorStatus;       
            $response['msg'] = $this->errorMsg;   
            $response['data'] =  [];
            
            if(!empty($user)){
                
                $playList = Playlist::where('user_id', Auth::user()->id)->get()->toArray();
                $allPlayList = [];
                foreach ($playList as $audio) {
                    
                    $playListDetail = [];
                    $song_list = [];
                    $audioId = [];
                    $songs = [];
                    if($audio['song_list'] != '' && !empty($audio['song_list'])){
                        $audioId = json_decode($audio['song_list']);
                    }
                    
                    if(!empty($audioId)){
                        $songs = Audio::select('*')->whereIn('id',$audioId)->where(['status' => '1'])->get()->toArray();
                    }                        
                    
                    if(!empty($songs)){
                        $song_list = $this->getSongArrayForm($songs);                        
                    }

                    $playListDetail['id'] = $audio['id'];
                    $playListDetail['user_id'] = $audio['user_id'];
                    $playListDetail['playlist_name'] = $audio['playlist_name'];
                    $playListDetail['song_list'] = $song_list;
                    $playListDetail['created_at'] = $audio['created_at'];
                    $playListDetail['updated_at'] = $audio['updated_at'];
                    $allPlayList[] = $playListDetail;
                }

                if(!empty($allPlayList)){
                    $response['status'] = $this->successStatus;
                    $response['msg'] = "Playlist is successfully found.";                        
                    $response['data'] = $allPlayList;
                }else{
                    $response['msg'] = "Playlist does not found.";   
                }    
                    
            }else{            
                $response['msg'] = 'Unauthenticated.';            
            }        
            $response['imagePath'] =  'images/audio/thumb/';
            $response['audioPath'] =  'images/audio/';
            return response()->json($response);        
        }    
    }
    
    
    public function update_playlist(Request $request){
        if($request->isMethod('post')){
            
            $user = Auth::user();
            $response['status'] = $this->errorStatus;          
    
            if(!empty($user)){                

                $playlistId = $request->playlist_id;
                $playlistName = $request->playlist_name;
                if(!empty($playlistId) && !empty($playlistName)){
                    $playlist = Playlist::find($playlistId);
                    if(!empty($playlist)){
                        $update = Playlist::where('id',$playlistId)->update(['playlist_name'=>$playlistName]);
                        $response['status'] = $this->successStatus;
                        $response['msg'] = "Playlist name is successfully updated.";                        
                    }else{
                        $response['msg'] = "Playlist does not found.";   
                    }    
                }else{                    
                    $response['msg'] = "Playlist id and name is required.";                    
                }            
            }else{            
                $response['msg'] = 'Unauthenticated.';            
            }        
            return response()->json($response);        
        }
    }
    
    public function add_playlist_music(Request $request){
        if($request->isMethod('post')){
            
            $user = Auth::user();
            $response['status'] = $this->errorStatus;       
            $response['msg'] = $this->errorMsg;            
    
            if(!empty($user)){                

                $playlistId = $request->playlist_id;
                $musicId = $request->music_id;
                $musictype = $request->music_type;

                if(!empty($playlistId) && !empty($musicId)){

                    $songs[] = $musicId;
                    $checkPlaylist = Playlist::where('id', $playlistId)->first();
                    
                    if(!empty($checkPlaylist)){

                        if(isset($musictype) && $musictype == 'ms_video'){
                            $checkVideoList = json_decode($checkPlaylist->video_list);
                            if(!empty($checkVideoList)){
                                if(!in_array( $musicId, $checkVideoList)){
                                    $video_list = array_merge($checkVideoList, $songs);
                                }else{
                                    $response['msg'] = __('frontWords.track').' '.__('frontWords.already_exist');
                                    return response()->json($response);
                                }
                            }else{
                                $video_list = [$musicId];
                            }
                            $update = Playlist::where('id', $playlistId)->update(['video_list'=>json_encode($video_list)]);                            
                            $response['msg'] = "Video is successfully added into playlist.";    

                        }else{

                            $checkSongList = json_decode($checkPlaylist->song_list);
                            if(!empty($checkSongList)){
                                if(!in_array( $musicId, $checkSongList)){
                                    $song_list = array_merge($checkSongList, $songs);
                                }else{
                                    $response['msg'] = __('frontWords.track').' '.__('frontWords.already_exist');
                                    return response()->json($response);
                                }
                            }else{
                                $song_list = [$musicId];
                            }
                            $update = Playlist::where('id', $playlistId)->update(['song_list'=>json_encode($song_list)]);
                            $response['msg'] = "Music is successfully added into playlist.";    
                        }
                       
                        $response['status'] = $this->successStatus; 

                    }
                    
                }else{                    
                    $response['msg'] = "Playlist id and music id is required.";                    
                }            
            }else{            
                $response['msg'] = 'Unauthenticated.';            
            }        
            return response()->json($response);        
        }
    }

    public function remove_playlist_music(Request $request){
        if($request->isMethod('post')){
            
            $user = Auth::user();
            $response['status'] = $this->errorStatus;       
            $response['msg'] = $this->errorMsg;            
    
            if(!empty($user)){                

                $playlistId = $request->playlist_id;
                $musicId = $request->music_id;
                $musictype = $request->music_type;

                if(!empty($playlistId) && !empty($musicId)){
                    $songs[] = $musicId;
                    $checkPlaylist = Playlist::where('id', $playlistId)->first();

                    if(!empty($checkPlaylist)){                                                  
                            
                        if(isset($musictype) && $musictype == 'ms_video'){
                            $videoList = json_decode($checkPlaylist->video_list);
                            if(in_array($musicId, $videoList)){
                                $key = array_search($musicId, $videoList);
                                unset($videoList[$key]);
                                $newArr = array_values($videoList);
                                $updateList = Playlist::where('id', $playlistId)->update(['video_list'=> $videoList]);     
                                $response['msg'] = "Video is successfully removed from playlist.";                                
                            }                        
                        }else{
                            $songList = json_decode($checkPlaylist->song_list);
                            if(in_array($musicId, $songList)){
                                $key = array_search($musicId, $songList);
                                unset($songList[$key]);
                                $newArr = array_values($songList);
                                $updateList = Playlist::where('id', $playlistId)->update(['song_list'=> $songList]);     
                                $response['msg'] = "Music is successfully removed from playlist.";                               
                            }                        
                        }                          
                            
                        $response['status'] = $this->successStatus;  
                        
                    }else{
                        $response['status'] = $this->errorStatus;
                        $response['msg'] = $this->errorMsg;    
                    }
                    
                }else{                    
                    $response['msg'] = "Playlist id and music id is required.";                    
                }            
            }else{            
                $response['msg'] = 'Unauthenticated.';            
            }        
            return response()->json($response);        
        }
    }
    


    /**
     * Music history/Recent List
     *
     * @return \Illuminate\Http\Response
     */
    public function music_history(Request $request){
        
        if($request->isMethod('get')){
            $user = Auth::user();
            $response['status'] = $this->errorStatus;       
            $response['msg'] = $this->errorMsg;   
            $response['data'] = [];

            if(!empty($user)){                
                
                $jsonSongList = UserHistory::select('audio_id')->where('user_id',Auth()->user()->id)->first();
                if($jsonSongList){
                    if($jsonSongList['audio_id'] != '[]'){                        
                        $songArr = [];                    
                        $songIds = array_reverse(json_decode($jsonSongList['audio_id']));                    
                        $ids_ordered = implode(',', $songIds);                    
                        $songs = Audio::select('*')->whereIn('id',$songIds)->where(['status' => '1'])->orderByRaw("FIELD(id, $ids_ordered)")
                        ->get()->toArray();
                        if(sizeof($songs) > 0){
                            $songArr = $this->getSongArrayForm($songs);
                        }             
                       
        
                        if(!empty($songArr)){
                            $response['status'] = $this->successStatus;
                            $response['msg'] = "Music history successfully found.";                        
                            $response['data'] = $songArr;
                            $response['totalCount'] = count($songArr);
                        }else{
                            $response['msg'] = "Music history does not found.";   
                        }    
                    }else{
                            $response['msg'] = "Music history does not found.";   
                        } 

                }else{
                    $response['msg'] = "Music history does not found.";
                }   
                    
            }else{            
                $response['msg'] = 'Unauthenticated.';            
            }        
            $response['imagePath'] = "images/audio/thumb/";
            $response['audioPath'] = "images/audio/";
            return response()->json($response);        
        }    
    }


    /**
     * Add & Remove Music To Music history/Recent Play List
     *
     * @return \Illuminate\Http\Response
     */
    public function addremove_musichistory(Request $request){

        if($request->isMethod('post')){
            $user = Auth::user();
            
            if(!empty($user)){
                
                $musicId = $request->music_id;
                $tag = $request->tag;
                
                if(!empty($musicId) && !empty($tag)){    
                    
                    $datas = $dataId = array();
                    $userid = Auth::user()->id;
                    $datas[] = $musicId;
                    $decodeIds = UserHistory::select('audio_id')->where(['user_id'=> $userid])->first();
                    if($decodeIds != '' && !empty($decodeIds)){
                        $dataId = json_decode($decodeIds['audio_id']);
                    }
                    
                    if($tag == 'add'){
                        if(!empty($decodeIds)){
                            
                            if( in_array($musicId, $dataId) ) {
                                
                                $countUpdate = Audio::where('id', $musicId)->update(['listening_count' => DB::raw('listening_count + 1')]);
                                $key = array_search($musicId, $dataId); 
                                unset($dataId[$key]);
                                $new_arr = array_values($dataId);
                                $update = UserHistory::where('user_id', $userid)->update(['audio_id' => json_encode($new_arr)]);
                                $new_arr = array_merge($dataId, $datas);
                                $create_album = UserHistory::where(['user_id'=>$userid])->update(['audio_id'=>json_encode($new_arr)]);
                            }else{
                                $new_arr = array_merge($dataId, $datas);
                                $create_album = UserHistory::where(['user_id'=>$userid])->update(['audio_id'=>json_encode($new_arr)]);
                            }
                                $response = ($create_album) ? ['status' => $this->successStatus, 'msg' => "Added to music history." ] : ['status' => $this->errorStatus, 'msg' => __('frontWords.something_wrong')];
                        }else{
                            $create_album = UserHistory::create(['audio_id' => json_encode($datas), 'user_id'=>$userid]);
                            $response = ($create_album) ? ['status' => $this->successStatus, 'msg' => "Added to music history."] : ['status' => $this->errorStatus, 'msg' => __('frontWords.something_wrong')];
                        }
                        
                    }
                    if($tag == 'remove'){
                        $key = array_search($musicId, $dataId); 
                        unset($dataId[$key]);
                        $new_arr = array_values($dataId);
                        $update = UserHistory::where('user_id', $userid)->update(['audio_id' => json_encode($new_arr)]);
                        $response = ($update) ? ['status' => $this->successStatus, 'msg' => "Removed to music history." ] : ['status' => $this->errorStatus, 'msg' => __('frontWords.something_wrong')];
                        
                    }
                    
                }else{
                    $response['status'] = $this->errorStatus;
                    $response['msg'] = "Music id and tag is required.";                   
                }
                
            }else{
                $response['status'] = $this->errorStatus;
                $response['msg'] = 'Unauthenticated.';
                $response['data'] =  new stdClass();
            }
            
            return response()->json($response);
        }
    }
    
    
        /**
     * Downloaded Music List
     *
     * @return \Illuminate\Http\Response
     */
    public function downloaded_music_list(Request $request){
        if($request->isMethod('get')){
            $user = Auth::user();
            $response['status'] = $this->errorStatus;       
            $response['msg'] = $this->errorMsg;   
            $response['data'] = [];

            if(!empty($user)){                
                $userId = Auth()->user()->id;
                
                $audioIds = UserAction::select('audio_id')->where(['user_id'=> $userId, 'download' => '1'])->get()->toArray();
                if(!empty($audioIds)){
                    
                    $songArr = [];                    

                    foreach ($audioIds as $audioId ) {
                        $songIds[] = $audioId['audio_id'];
                    }                    
                    
                    $ids_ordered = implode(',', array_reverse($songIds));   

                    $songs = Audio::select('*')->whereIn('id',$songIds)->where(['status' => '1'])->orderByRaw("FIELD(id, $ids_ordered)")
                    ->get()->toArray();
                    if(sizeof($songs) > 0){
                        $songArr = $this->getSongArrayForm($songs);
                    }        
    
                    if(!empty($songArr)){
                        $response['status'] = $this->successStatus;
                        $response['msg'] = "Downloaded Music successfully found.";                        
                        $response['data'] = $songArr;
                        $response['totalCount'] = count($songArr);
                    }else{
                        $response['msg'] = "Downloaded music does not found.";
                    }    
                }else{
                    $response['msg'] = "Downloaded music does not found.";
                }   
                    
            }else{            
                $response['msg'] = 'Unauthenticated.';            
            }       
            $response['imagePath'] = "images/audio/thumb/";
            $response['audioPath'] = "images/audio/";
            return response()->json($response);        
        }    
    }


    /**
     * Add & Remove Music To Music Download List
     *
     * @return \Illuminate\Http\Response
     */
    public function addremove_downloadmusic(Request $request){

        if($request->isMethod('post')){
            $user = Auth::user();
            
            if(!empty($user)){
                
                $musicId = $request->music_id;
                $tag = $request->tag;
                
                if(!empty($musicId) && !empty($tag)){                        
                    
                    $userid = Auth::user()->id; 
                    $checkAudio = UserAction::where(['audio_id' => $musicId,'user_id'=> $userid,'download' => '1'])->first();
                    
                    if($tag == 'add'){
                        if(empty($checkAudio)){
                            $create_download = UserAction::create(['audio_id' => $musicId, 'user_id'=>$userid ,'download' =>'1']);
                            $response = ($create_download) ? ['status' => $this->successStatus, 'msg' => "Added to download."] : ['status' => $this->errorStatus, 'msg' => __('frontWords.something_wrong')];
                        }else{
                            $response = ['status' => $this->errorStatus, 'msg' => "Music already exist in download list." ];
                        }
                    }
                    
                    if($tag == 'remove'){
                        if(!empty($checkAudio)){  
                            $removeDownload = $checkAudio->delete();
                            $response = ($removeDownload) ? ['status' => $this->successStatus, 'msg' => "Music successfully removed from download." ] : ['status' => $this->errorStatus, 'msg' => __('frontWords.something_wrong')];
                            
                        }else{
                            $response = ['status' => $this->errorStatus, 'msg' => "Music not found in download list." ];
                        }
                    }
                }else{
                    $response['status'] = $this->errorStatus;
                    $response['msg'] = "Music id and tag is required.";                   
                }
                
            }else{
                $response['status'] = $this->errorStatus;
                $response['msg'] = 'Unauthenticated.';
                $response['data'] =  new stdClass();
            }
            
            return response()->json($response);
        }
    }
    
    /**
     * Get Our Plan And All Details
     *
     * @return \Illuminate\Http\Response
     */
    public function plan_list(Request $request){
        if($request->isMethod('get')){
            $user = Auth::user();
            $response['status'] = $this->errorStatus;       
            $response['msg'] = $this->errorMsg;   
            $response['data'] = [];
            $plans = [];

            if(!empty($user)){                

                $myPlan = User::select('plan_id')->where('id',Auth()->user()->id)->first();
                if($myPlan->plan_id > 0){
                    $plans['current_plan'] = Plan::where(['status' => '1','id' => $myPlan->plan_id])->first();
                }else{
                    $plans['current_plan'] = new stdClass();
                }
                $plans['all_plans'] = Plan::where('status','1')->get()->toArray();                
                if(!empty($plans)){
                    $response['status'] = $this->successStatus;
                    $response['msg'] = "Plan is successfully found.";                        
                    $response['data'][] = $plans;
                }else{
                    $response['msg'] = "Plan does not found.";   
                }    
                    
            }else{            
                $response['msg'] = 'Unauthenticated.';            
            }        
            $response['imagePath'] = "images/plan/";
            $response['is_month_days'] = "0->days,1->months";
            return response()->json($response);        
        }    
    }
    
    /**
     * Get Coupon list
     *
     * @return \Illuminate\Http\Response
     */
    public function get_user_coupon_list(Request $request){ 

        if($request->isMethod('get')){
            $user = Auth::user();
            $response['status'] = $this->errorStatus;       
            $response['msg'] = $this->errorMsg;   
            $response['data'] = [];
            $userCoupon = [];

            if(!empty($user)){
                if(Auth::user()->plan_id == '0'){
                    
                    $userCoupons = Coupon::where(['applicable_on'=>'0', 'status'=> '1',['expiry_date', '>=', date('Y-m-d')]])->get()->toArray();
                    if(!empty($userCoupons)){
                        foreach($userCoupons as $coupon){
                            if($coupon['description'] == ''){
                                $coupon['description'] = '';
                            }
                            $userCoupon[] = $coupon;     
                        }
                    }
                    
                }else{
                    $freeAndPlanCoupon = [];
                    $userPlanCoupon = [];
                    $userAllCoupon = [];
                    
                    $planId = Auth::user()->plan_id;                    
                    $userCoupons = Coupon::where(['status'=> '1',['expiry_date', '>=', date('Y-m-d')]])->get()->toArray();
                    if(!empty($userCoupons)){
                        
                        foreach($userCoupons as $coupon){
                            if($coupon != '' && !empty($coupon['plan_id'])){
                                $dataId = json_decode($coupon['plan_id']);
                                if(in_array($planId , $dataId)) {
                                    $userPlanCoupon[] = $coupon;             
                                }
                            }
                        }
                        $userAllCoupon = Coupon::where(['applicable_on'=>'0','status'=>'1',['expiry_date', '>=', date('Y-m-d')]])->get()->toArray();
                        $freeAndPlanCoupon = array_merge($userPlanCoupon,$userAllCoupon);
                        foreach($freeAndPlanCoupon as $coupon){
                            
                            if($coupon['description'] == ''){
                                $coupon['description'] = '';
                            }
                            $userCoupon[] = $coupon;
                        }
                        
                    }
                }
                
                if(!empty($userCoupon)){      
                    $response['status'] = $this->successStatus;
                    $response['msg'] = "Coupon successfully found.";                        
                    $response['discount_type'] = "1 = dollar ,2 = percentage";
                    $response['data'] = $userCoupon;
                            
                }else{
                    $response['msg'] = "Sorry no coupon found.";    
                }
                return response()->json($response);        
                    

            }else{            
                $response['msg'] = 'Unauthenticated.';            
            }       
        }  
    }
    
    /**
     * Get Our Plan Details
     *
     * @return \Illuminate\Http\Response
     */
    public function user_coupon_detail(Request $request){ 

        if($request->isMethod('post')){
            $user = Auth::user();
            $response['status'] = $this->errorStatus;       
            $response['msg'] = $this->errorMsg;   
            $response['data'] = [];
            
            if(!empty($user)){
                $coupon = $request->coupon_code;
                if(!empty($coupon)){                    
                    $couponDetail = Coupon::where(DB::raw('BINARY `coupon_code`'),$coupon)->where('status','1')->first();
                    
                    if(!empty($couponDetail)){
                        if($couponDetail['description'] == ''){
                            $couponDetail['description'] = '';
                        } 
                        if($couponDetail->applicable_on == '0'){
                            $response['status'] = $this->successStatus;
                            $response['msg'] = "Coupon detail successfully found.";                        
                            $response['data'] = $couponDetail;
                        }else{
                            $plan_id = auth()->user()->plan_id;
                            $planCouponDetail = Coupon::where(DB::raw('BINARY `coupon_code`'),$coupon)->where(['status'=>'1'])->first();
                            if($planCouponDetail != '' && !empty($planCouponDetail)){
                                $dataId = json_decode($planCouponDetail->plan_id);
                            }
                            if( in_array($plan_id, $dataId) ) {
                                $response['status'] = $this->successStatus;
                                $response['msg'] = "Coupon detail successfully found.";                        
                                $response['data'] = $planCouponDetail;
                            }else{
                                $response['msg'] = "Coupon code is not valid.";
                                $response['data'] = new stdClass();
                            }
                        }
                        
                    }else{
                        $response['msg'] = "Coupon code is not valid.";   
                    }    
                }else{
                    $response['msg'] = "Coupon Code is required.";    
                }
                
                $response['discount_type'] = "1 = dollar ,2 = percentage";
                return response()->json($response);        
                    
            }else{            
                $response['msg'] = 'Unauthenticated.';            
            }       
        }  
    }


    /**
     * Get All Yt Playlist by Channel Id
     *
     * @return \Illuminate\Http\Response
     */
    public function ytPlaylists(Request $request) 
    {
        if($request->isMethod('get')){
            $user = Auth::user();
            
            if(!empty($user)){

                $ytPlaylist = array();    
                $is_youtube = Settings::where('name', 'is_youtube')->first();   
                
                if(!empty(env('YOUTUBE_CHANNEL_KEY'))){
                    
                    try {
                        $ytPlaylist = Youtube::getPlaylistsByChannelId(env('YOUTUBE_CHANNEL_KEY'));                    
                        if(!empty($ytPlaylist)){
                            
                            $response['status'] = $this->successStatus;
                            $response['msg'] = "Youtube playlists successfully found.";
                            $response['data'] =  $ytPlaylist;
                        }else{
                            $response['status'] = $this->errorStatus;
                            $response['msg'] = 'Sorry no youtube playlists found.';
                            $response['data'] =  [];
                        }
                    }catch (\Exception $e) {
                        $response['status'] = $this->errorStatus;
                        $response['msg'] = 'Sorry no youtube playlists found.';
                        $response['data'] =  [];
                    } 
                    
                }else{
                    $response['status'] = $this->errorStatus;
                    $response['msg'] = 'Sorry Channel-id does not exist.';
                    $response['data'] =  [];
                }

                
            }else{
                $response['status'] = $this->errorStatus;
                $response['msg'] = 'Unauthenticated.';
                $response['data'] =  [];
            }
            return response()->json($response);
        }
    } 




}
