    <div class="ms_music_wrapper common_pages_space"> 
        <div class="ms_music_inner">
            <div class="ms_music_row">         
                <div class="ms_music_left">    
                    
                    @php 
                        if(sizeof($top_album) > 0){
                    @endphp
                        <div class="music_center_slider">
                            <div class="swiper-container">
                                <div class="swiper-wrapper">
                    @php
                                    $albums_id = json_decode($top_album[0]->top_album);
                                        if(!empty($albums_id)){
                                            foreach($albums_id as $album_id){
                                                $albumsData = select(['column' => '*', 'table' => 'albums', 'where' => ['id'=>$album_id] ]);
                                                if(!empty($albumsData)){
                                                    foreach($albumsData as $album){
                                                    $artist_name = get_artist_name(['album_id'=>$album->id]);
                    @endphp
                                                    <div class="swiper-slide">
                                                        <div class="music_center_mainbox text-center">
                                                            <div class="music_center_img">
                                                                @if($album->image != '' && file_exists(public_path('images/album/'.$album->image)))
                                                                    <img src="{{ asset('images/album/'.$album->image) }}" alt="" class="img-fluid">
                                                                @else
                                                                    <img src="{{ dummyImage('album') }}" alt="" class="img-fluid">
                                                                @endif
                                                            </div>
                                                            <div class="music_center_info">
                                                                <a href="javascript:void(0)" class="music_center_title limited_text_line getAjaxRecord" data-type="audio" data-url="{{ url('album/single/'.$album->id.'/'.$album->album_slug) }}">{{ $album->album_name }}</a>
                                                                <p class="music_center_sub limited_text_line"> {{ rtrim(($artist_name != '' ? $artist_name : '') , ',') }}</p>
                                                                <div class="music_center_details">
                                                                    <a class="ms_btn getAjaxRecord" data-type="album" data-url="{{ url('album/single/'.$album->id.'/'.$album->album_slug) }}" href="javascript:void(0)"> Play All</a>
                                                                    
                                                                    @if(!empty($userPlan) && $userPlan->is_download == 1)
                                                                        <a href="javascript:void(0);" class="download_list" data-musicid="{{ $album->id }}" data-type="album">
                                                                            <span class="music_center_dwld">
                                                                                <i class="center_dwld_icon"></i>
                                                                            </span>
                                                                        </a>
                                                                    @else
                                                                        <a class="getAjaxRecord" data-type="pricing_plan" data-url="{{ route('pricing-plan') }}" href="javascript:void(0)">
                                                                            <span class="music_center_dwld">
                                                                                <i class="center_dwld_icon"></i>
                                                                            </span>
                                                                        </a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                            
                    @php } } } } @endphp
                                </div>
                            </div>                                
                            <!-- Add Arrows -->
                            <div class="slider_music_controls">                                
                                <!-- <span class="swiper-music-next"><img src="images/svg/left_arrow.svg" alt="Arrow"></span>
                                <span class="swiper-music-prev"><img src="images/svg/left_arrow.svg" alt="Arrow"></span> -->
    
                                <span class="swiper-music-next">
                                    <svg xmlns:xlink="http://www.w3.org/1999/xlink" width="6px" height="10px"><path fill-rule="evenodd" fill="rgb(142, 165, 194)" d="M5.715,8.455 L2.316,5.062 C2.275,5.022 2.275,4.957 2.316,4.918 L5.715,1.525 C6.065,1.176 6.065,0.610 5.715,0.261 L5.715,0.261 C5.365,-0.088 4.798,-0.088 4.448,0.261 L0.199,4.501 C-0.072,4.771 -0.072,5.209 0.199,5.479 L4.448,9.719 C4.798,10.068 5.365,10.068 5.715,9.719 L5.715,9.719 C6.065,9.370 6.065,8.804 5.715,8.455 Z"/></svg>
                                </span>
                                <span class="swiper-music-prev">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="6px" height="10px"><path fill-rule="evenodd" fill="rgb(142, 165, 194)" d="M5.715,8.455 L2.316,5.062 C2.275,5.022 2.275,4.957 2.316,4.918 L5.715,1.525 C6.065,1.176 6.065,0.610 5.715,0.261 L5.715,0.261 C5.365,-0.088 4.798,-0.088 4.448,0.261 L0.199,4.501 C-0.072,4.771 -0.072,5.209 0.199,5.479 L4.448,9.719 C4.798,10.068 5.365,10.068 5.715,9.719 L5.715,9.719 C6.065,9.370 6.065,8.804 5.715,8.455 Z"/></svg>
                                </span>
                            </div>
                        </div>
                    @php } @endphp
                    <h2 class="music_listwrap_tttl mt-0">{{ __('frontWords.top_15') }}</h2>
                    <div class="music_listwrap">
                        <div class="ms_songslist_box">
                            <ul class="ms_songlist">
                                    @if(sizeof($top_audio) > 0)                                 
                                        @php                                                 
                                            $cnt = 1;
                                            $i= 1;    
                                            $audios_id = json_decode($top_audio[0]->top_audio);    
                                        @endphp
                                            @foreach($audios_id as $audio_id)
                                                @php
                                                    $audios = select(['column' => '*', 'table' => 'audio', 'where' => ['id'=>$audio_id] ]);
                                                @endphp

                                                @if(!empty($audios))
                                                    
                                                        @foreach($audios as $audio)
                                                            @php
                                                                $getArtist = json_decode($audio->artist_id);
                                                                $artist_name = '';
                                                            @endphp
                                                                
                                                            @foreach($getArtist as $artistid)
                                                                @php
                                                                    $artists = select(['column'=>'artist_name','table'=>'artists','where'=>['id'=>$artistid] ]);
                                                                @endphp
                                                                @if(count($artists) > 0)
                                                                    @php
                                                                        $artist_name .= $artists[0]->artist_name;
                                                                    @endphp
                                                                @endif
                                                            @endforeach
                                                            @php 
                                                                $getLikeDislikeAudio = getFavDataId(['column' => 'audio_id', 'audio_id' => $audio->id]);
                                                            @endphp
                                                           

                                                            <li>
                                                                <div class="ms_songslist_inner">
                                                                    <div class="ms_songslist_left">
                                                                        <div class="songslist_number">
                                                                            <h4 class="songslist_sn">{{ $i++ }}</h4>
                                                                            <span class="songslist_play play_music" data-musicid="{{ $audio->id }}" data-musictype="audio" data-url="{{ url('/songs') }}"><img src="{{ asset('images/svg/play_songlist.svg') }}" alt="" class="img-fluid"/></span>
                                                                        </div> 
                                                                        <div class="songslist_details">
                                                                            <div class="songslist_thumb">
                                                                                @if($audio->image != '' && file_exists(public_path('images/audio/thumb/'.$audio->image)))
                                                                                    <img src="{{ asset('images/audio/thumb/'.$audio->image) }}" alt="">
                                                                                @else
                                                                                    <img src="{{ dummyImage('audio') }}" alt="" class="img-fluid">
                                                                                @endif                                                               
                                                                            </div>
                                                                            <div class="songslist_name">
                                                                                
                                                                                <h3 class="song_name play_music limited_text_line" data-musicid="{{ $audio->id }}" data-musictype="audio" data-url="{{ url('/songs') }}"><a href="javascript:void(0);">{{ $audio->audio_title }}</a></h3>
                                                                                <p class="song_artist limited_text_line">{{ $artist_name }} </p>
                                                                            </div> 
                                                                        </div> 

                                                                    </div>
                                                                    <div class="ms_songslist_right">
                                                                        <span class="ms_songslist_like addToFavourite" data-favourite="{{ $audio->id }}" data-type="audio">
                                                                            @if($getLikeDislikeAudio == 1)
                                                                            <svg width="19px" height="19px" id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 391.8 391.8"><defs><style>.cls-1{fill:#d80027;}</style></defs><title>{{ 'Remove From'.__('frontWords.favourites') }}</title><path class="cls-1" d="M280.6,43.8A101.66,101.66,0,0,1,381.7,144.9c0,102-185.8,203.1-185.8,203.1S10.2,245.5,10.2,144.9A101.08,101.08,0,0,1,111.3,43.8h0A99.84,99.84,0,0,1,196,89.4,101.12,101.12,0,0,1,280.6,43.8Z"></path></svg>
                                                                            @else
                                                                            <svg xmlns:xlink="http://www.w3.org/1999/xlink" width="17px" height="16px"><path fill-rule="evenodd" fill="rgb(124, 142, 165)" d="M11.777,-0.000 C10.940,-0.000 10.139,0.197 9.395,0.585 C9.080,0.749 8.783,0.947 8.506,1.173 C8.230,0.947 7.931,0.749 7.618,0.585 C6.874,0.197 6.073,-0.000 5.236,-0.000 C2.354,-0.000 0.009,2.394 0.009,5.337 C0.009,7.335 1.010,9.428 2.986,11.557 C4.579,13.272 6.527,14.702 7.881,15.599 L8.506,16.012 L9.132,15.599 C10.487,14.701 12.436,13.270 14.027,11.557 C16.002,9.428 17.004,7.335 17.004,5.337 C17.004,2.394 14.659,-0.000 11.777,-0.000 ZM5.236,2.296 C6.168,2.296 7.027,2.738 7.590,3.507 L8.506,4.754 L9.423,3.505 C9.986,2.737 10.844,2.296 11.777,2.296 C13.403,2.296 14.727,3.660 14.727,5.337 C14.727,6.734 13.932,8.298 12.364,9.986 C11.114,11.332 9.604,12.490 8.506,13.255 C7.409,12.490 5.899,11.332 4.649,9.986 C3.081,8.298 2.286,6.734 2.286,5.337 C2.286,3.660 3.610,2.296 5.236,2.296 Z"/></svg>
                                                                            @endif
                                                                        </span> 
                                                                        <span class="ms_songslist_time">{{ $audio->audio_duration }}</span>
                                                                        <div class="ms_songslist_more">
                                                                            <span class="songslist_moreicon"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="4px" height="20px"><path fill-rule="evenodd" fill="rgb(124, 142, 165)" d="M2.000,12.000 C0.895,12.000 -0.000,11.105 -0.000,10.000 C-0.000,8.895 0.895,8.000 2.000,8.000 C3.104,8.000 4.000,8.895 4.000,10.000 C4.000,11.105 3.104,12.000 2.000,12.000 ZM2.000,4.000 C0.895,4.000 -0.000,3.105 -0.000,2.000 C-0.000,0.895 0.895,-0.000 2.000,-0.000 C3.104,-0.000 4.000,0.895 4.000,2.000 C4.000,3.105 3.104,4.000 2.000,4.000 ZM2.000,16.000 C3.104,16.000 4.000,16.895 4.000,18.000 C4.000,19.105 3.104,20.000 2.000,20.000 C0.895,20.000 -0.000,19.105 -0.000,18.000 C-0.000,16.895 0.895,16.000 2.000,16.000 Z"/></svg></span>
                                                                            <ul class="ms_common_dropdown ms_songslist_dropdown">
                                                                                <li><a href="javascript:void(0);" class="add_to_queue" data-musicid="{{ $audio->id }}" data-musictype="audio"><span class="opt_icon" title="Add To Queue"><span class="icon icon_queue"></span></span>{{ __("frontWords.add_to_queue") }}</a></li>         
                                                                                
                                                                                <li>
                                                                                    <a href="javascript:void(0);" class="ms_add_playlist" data-musicid="{{ $audio->id }}">
                                                                                        <span class="common_drop_icon drop_playlist"></span>
                                                                                        {{ __("frontWords.add_to_playlist") }}
                                                                                    </a>
                                                                                </li>
                                                                                <li>
                                                                                    <a href="javascript:void(0);" class="ms_share_music" data-shareuri="{{ url('audio/single/'.$audio->id.'/'.$audio->audio_slug) }}" data-sharename="'.$audio->audio_title.'">
                                                                                        <span class="common_drop_icon drop_share"></span>{{ __("frontWords.share") }}
                                                                                    </a>
                                                                                </li>
                                                                                <li>
                                                                                    @if(isset($audio->download_price) && !empty($audio->download_price))
                                                                                        <input type="hidden" class="getAudioAmountToDownload" value="{{ $audio->download_price }}">
                                                                                        @if(Auth::check())
                                                                                                
                                                                                            @php 
                                                                                                $buyedAudios = json_decode(auth()->user()->audio_download_list); 
                                                                                            @endphp
                                                                                            @if(!empty($buyedAudios))
                                                                                                @if(in_array($audio->id, $buyedAudios))
                                                                                                    <a href="javascript:void(0);" class="artistDownloadTrack download_artist_track" data-musicid="{{ $audio->id }}" data-type="audio">     
                                                                                                        <span class="opt_icon common_drop_icon drop_downld"> 
                                                                                                            <span class="icon icon_download"></span>
                                                                                                        </span>
                                                                                                        {{ __("frontWords.download") }}
                                                                                                    </a>
                                                                                                @else
                                                                                                    <a href="javascript:void(0);" class="buy_to_download_audio" data-musicid="{{ $audio->id }}" data-type="audio">     
                                                                                                        <span class="opt_icon common_drop_icon drop_downld">
                                                                                                            <span class="icon icon_download"></span>
                                                                                                        </span>
                                                                                                        {{ __("frontWords.buy_to_download") }}
                                                                                                    </a>
                                                                                                @endif
                                                                                            @else
                                                                                                <a href="javascript:void(0);" class="buy_to_download_audio" data-musicid="{{ $audio->id }}" data-type="audio">     
                                                                                                    <span class="opt_icon common_drop_icon drop_downld">
                                                                                                        <span class="icon icon_download"></span>
                                                                                                    </span>
                                                                                                    {{ __("frontWords.buy_to_download") }}
                                                                                                </a>
                                                                                            @endif
                                                                                                
                                                                                        @else
                                                                                            <a href="javascript:void(0);" class="buy_to_download_audio" data-musicid="{{ $audio->id }}" data-type="audio">     
                                                                                                <span class="opt_icon common_drop_icon drop_downld">
                                                                                                    <span class="icon icon_download"></span>
                                                                                                </span>
                                                                                                {{ __("frontWords.buy_to_download") }}
                                                                                            </a>                                                           
                                                                                        @endif                                             

                                                                                    @elseif(!empty($userPlan) && $userPlan->is_download == 1) 
                                                                                        @if($audio->aws_upload == 1)
                                                                                            <a href=" {{ getSongAWSUrlHtml($audio) }} ">
                                                                                                <span class="common_drop_icon drop_downld"></span>{{ __("frontWords.download_now") }} 
                                                                                            </a> 
                                                                                        @else    
                                                                                            <a href="javascript:void(0);" class="download_track" data-musicid="{{ $audio->id }}">
                                                                                                <span class="common_drop_icon drop_downld"></span>{{ __("frontWords.download_now") }} 
                                                                                            </a>
                                                                                        @endif
                                                                                     
                                                                                    @elseif(empty($audio->download_price) && empty($userPlan)) 
                                                                                        <a href="{{ route('pricing-plan') }}">
                                                                                            <span class="opt_icon common_drop_icon drop_downld">
                                                                                                <span class="icon icon_download"></span>
                                                                                            </span>{{ __("frontWords.download") }}  
                                                                                        </a>                                                         
                                                                                    
                                                                                    @endif
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                    @endforeach
                                                    
                                                @endif
                                            @endforeach
                                        @else
                                            <div class="col-lg-12">
                                                <div class="ms_empty_data">
                                                    <p>{{ __("frontWords.no_track") }}</p>
                                                </div>
                                            </div>                            
                                        @endif                  
                            </ul>
                        </div>
                    </div>
                </div>                
            
                <div class="ms_music_right ms_track_slides">                   


                    @if(!empty($is_youtube) && $is_youtube == 1 && !empty($ytPlaylists['results']))   

                        <!-- YT Top Tracks Start -->
                        @if(isset($popularYtVideos) && sizeof($popularYtVideos) > 0)
                            <!-- Youtube Playlists -->
                            <div class="ms_artist_slider also_like_slider">
                                <div class="slider_heading_wrap">
                                    <div class="slider_cheading">
                                        <h4 class="cheading_title">{{ __("adminWords.youtube").' '.__("frontWords.top_track") }} &nbsp;</h4> 
                                    </div>
                                    <!-- Add Arrows --> 
                                    <div class="slider_cmn_controls">
                                        <div class="slider_cmn_nav "><span class="swiper-button-next1 slider_nav_next"></span></div> 
                                        <div class="slider_cmn_nav"><span class="swiper-button-prev1 slider_nav_prev"></span></div>
                                    </div>
                                </div>
                                <div class="ms_artist_innerslider">
                                    <div class="swiper-container">
                                        <div class="swiper-wrapper">
                                            @php 
                                                $artists = ''; 
                                                $ytImage = '';   
                                                $videoId = '';

                                                foreach($popularYtVideos as $ytVideo){                                                

                                                    if(isset($ytVideo->snippet->thumbnails->medium->url) && !empty($ytVideo->snippet->thumbnails->medium->url)){
                                                        $ytImage = $ytVideo->snippet->thumbnails->medium->url;
                                                    }elseif(isset($ytVideo->snippet->thumbnails->high->url) && $ytVideo->snippet->thumbnails->high->url){
                                                        $ytImage = $ytVideo->snippet->thumbnails->high->url;
                                                    }elseif(isset($ytVideo->snippet->thumbnails->default->url) && !empty($ytVideo->snippet->thumbnails->default->url)){
                                                        $ytImage = $ytVideo->snippet->thumbnails->default->url;
                                                    }else{ $ytImage = 'images/yt_music.webp'; }

                                                    if(isset($ytVideo->id) && !empty($ytVideo->id)){
                                                        $videoId = $ytVideo->id;
                                                    }
                                                    
                                                    if($ytVideo->snippet->thumbnails != '' ){
                                                        $img = '<img src="'.asset($ytImage).'" alt="">';
                                                    }else{
                                                        $img = '<img src="'.dummyImage('audio').'" alt="" class="img-fluid">';
                                                    }
                                            @endphp
                                                    <div class="swiper-slide play_btn">
                                                        <div class="slider_cbox slider_artist_box text-center play_box_container">
                                                            <div class="slider_cimgbox slider_artist_imgbox play_box_img">
                                                                @if($ytVideo->snippet->thumbnails != '' )
                                                                    <img src="{{ asset($ytImage) }}" alt="" class="img-fluid">
                                                                @else
                                                                    <img src="{{ dummyImage('audio') }}" alt="" class="img-fluid"> 
                                                                @endif  
                                                            </div>                                                            

                                                            <div class="ms_play_icon play_btn yt_music" data-musicid="{{ $videoId }}" data-title="{{ $ytVideo->snippet->title }}" data-musictype="ytBrowseSearch" data-image="{{ $ytImage }}">
                                                                <img src="{{ asset('assets/images/svg/play.svg') }}" alt="">
                                                            </div>

                                                            <div class="slider_ctext slider_artist_text">
                                                                <a class="slider_ctitle slider_artist_ttl limited_text_line yt_music" data-musicid="{{ $videoId }}" data-title="{{ $ytVideo->snippet->title }}" data-musictype="ytBrowseSearch" data-image="{{ $ytImage }}" href="javascript:void(0)">{{ $ytVideo->snippet->title }}</a>          
                                                            </div>
                                                        </div>
                                                        </div>';
                                            @php  } @endphp                                      
                                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>                            
                        @endif
                        <!-- YT Top Tracks End -->


                        <!-- YT Playlists Start -->
                        @php  
                            $y = 2;                         
                            $t = count($ytPlaylists['results']);

                        @endphp

                        @foreach($ytPlaylists['results'] as $ytplaylists)

                            @php $y++; @endphp
                            <div class="ms_artist_slider also_like_slider{{$y}}">
                                <div class="slider_heading_wrap">
                                    <div class="slider_cheading">
                                        <h4 class="cheading_title limited_text_line">{{ $ytplaylists->snippet->title }} &nbsp;</h4>
                                    </div>
                                    <!-- Add Arrows -->
                                    <div class="slider_cmn_controls">
                                        <div class="slider_cmn_nav"><span class="swiper-button-next{{$y}} slider_nav_next"></span></div>
                                        <div class="slider_cmn_nav"><span class="swiper-button-prev{{$y}} slider_nav_prev"></span></div>
                                    </div>
                                </div>
                                                
                                @php
                                    $playlists = getYtPlaylistDetailById($ytplaylists->id);                                
                                @endphp


                                    <div class="ms_artist_innerslider">
                                        <div class="swiper-container">
                                            <div class="swiper-wrapper">                                                
                                            @foreach($playlists['results'] as $ytplaylist)        
                                                @php                            
                                                    $ytImage = '';    
                                                    $videoId = '';                                                  
                                                        
                                                    if(isset($ytplaylist->snippet->thumbnails->medium->url) && !empty($ytplaylist->snippet->thumbnails->medium->url)){
                                                        $ytImage = $ytplaylist->snippet->thumbnails->medium->url;
                                                    }elseif(isset($ytplaylist->snippet->thumbnails->high->url) && $ytplaylist->snippet->thumbnails->high->url){
                                                        $ytImage = $ytplaylist->snippet->thumbnails->high->url;
                                                    }elseif(isset($ytplaylist->snippet->thumbnails->default->url) && !empty($ytplaylist->snippet->thumbnails->default->url)){
                                                        $ytImage = $ytplaylist->snippet->thumbnails->default->url;
                                                    }else{ $ytImage = 'images/yt_music.webp'; }

                                                    if(isset($ytplaylist->snippet->resourceId->videoId) && !empty($ytplaylist->snippet->resourceId->videoId)){
                                                        $videoId = $ytplaylist->snippet->resourceId->videoId;
                                                    }   
                                                @endphp
                                                @if(!empty($videoId)) 
                                                    <div class="swiper-slide play_btn play_icon_btn">
                                                        <div class="slider_cbox slider_artist_box text-center play_box_container">
                                                            <div class="slider_cimgbox slider_artist_imgbox play_box_img">
                                                                @if($ytplaylist->snippet->thumbnails != '' )
                                                                    <img src="{{ asset($ytImage) }}" alt="" class="img-fluid">
                                                                @else
                                                                    <img src="{{ dummyImage('audio') }}" alt="" class="img-fluid">
                                                                @endif      
                                                            </div>

                                                            <div class="ms_play_icon play_btn yt_music" data-musicid="{{ $videoId }}" data-title="{{ $ytplaylist->snippet->title }}" data-musictype="ytBrowseSearch" data-image="{{ $ytImage }}">
                                                                <img src="{{ asset('images/svg/play.svg') }}" alt="play icone">
                                                            </div>
                                                            <div class="slider_ctext slider_artist_text">
                                                                <a href="javascript:void(0)" class="slider_ctitle yt_music slider_artist_ttl limited_text_line" data-musicid="{{ $videoId }}" data-title="{{ $ytplaylist->snippet->title }}" data-musictype="ytBrowseSearch" data-image="{{ $ytImage }}">{{ $ytplaylist->snippet->title }}</a>           
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif                                   
                                            @endforeach
                                                               
                                            </div>
                                        </div>
                                    </div>
                                        
                                    <script>
                                        var t = parseInt(`{{$t}}`)+3;
                                        for (let y = 3; y < t; y++) {
                                            var swiper = new Swiper('.also_like_slider'+y+' .swiper-container', {
                                                slidesPerView: 4,
                                                spaceBetween: 30,
                                                loop: true,
                                                speed: 1500,
                                                navigation: {
                                                    nextEl: '.swiper-button-next'+y,
                                                    prevEl: '.swiper-button-prev'+y,
                                                },
                                                breakpoints: {
                                                    1800: {
                                                        slidesPerView: 3,
                                                    },
                                                    1600: {
                                                        slidesPerView: 3,
                                                        spaceBetween: 20,
                                                    },
                                                    1500: {
                                                        slidesPerView: 2,
                                                    },
                                                    1399: {
                                                        slidesPerView: 4,
                                                        spaceBetween: 10,
                                                    },
                                                    1024: {
                                                        slidesPerView: 3,
                                                        spaceBetween: 10,
                                                    },
                                                    992: {
                                                        slidesPerView: 4,
                                                        spaceBetween: 10,
                                                    },
                                                    800: {
                                                        slidesPerView: 3,
                                                        spaceBetween: 10,
                                                    },
                                                    700: {
                                                        slidesPerView: 2,
                                                        spaceBetween: 15,
                                                    },
                                                    480: {
                                                        slidesPerView: 1,
                                                    }
                                                },
                                            });
                                        }
                                    </script>
                                        
                            </div>
                        @endforeach    
                        <!-- YT Playlists End -->
                        
                    @endif


                    <!-- Admin Added Playlists -->
                    @if(sizeof($admin_playlist) > 0)                      
                            @php 
                                $i = 1; 
                                $c = count($admin_playlist);
                            @endphp
                            @foreach($admin_playlist as $playlist)
                                
                                @php $i++; @endphp
                                <div class="ms_artist_slider also_like_slider{{$i}}">
                                    <div class="slider_heading_wrap">
                                        <div class="slider_cheading">
                                            <h4 class="cheading_title limited_text_line">{{ $playlist['playlist_title'] }} &nbsp;</h4>
                                        </div>
                                        <!-- Add Arrows -->
                                        <div class="slider_cmn_controls">
                                            <div class="slider_cmn_nav"><span class="swiper-button-next{{$i}} slider_nav_next"></span></div>
                                            <div class="slider_cmn_nav"><span class="swiper-button-prev{{$i}} slider_nav_prev"></span></div>
                                        </div>
                                    </div>
                                    
                                    @if(sizeof($playlist['playlist_audio']) > 0)

                                        <div class="ms_artist_innerslider">
                                            <div class="swiper-container">
                                                <div class="swiper-wrapper">
                                                    <?php
                                                        foreach($playlist['playlist_audio'] as $audio){
                                                            $getArtist = json_decode($audio->artist_id);
                                                            $artist_name = '';
                                                            foreach($getArtist as $artistName){
                                                                $artists = select(['column'=>'artist_name','table'=>'artists','where'=>['id'=>$artistName] ]);
                                                                if(count($artists) > 0){
                                                                    $artist_name .= $artists[0]->artist_name.',';
                                                                }
                                                            }
                                                            
                                                            if($audio->image != '' && file_exists(public_path('images/audio/thumb/'.$audio->image))){
                                                                $img = '<img src="'.asset('images/audio/thumb/'.$audio->image).'" alt="" class="img-fluid">';
                                                            }else{
                                                                $img = '<img src="'.dummyImage('audio').'" alt="" class="img-fluid">';
                                                            }
                                                            echo'<div class="swiper-slide play_btn play_icon_btn">
                                                                    <div class="slider_cbox slider_artist_box text-center play_box_container">
                                                                        <div class="slider_cimgbox slider_artist_imgbox play_box_img">'.$img.'</div>
                                                                        <div class="ms_play_icon play_music" data-musicid="'.$audio->id.'" data-musictype="audio" data-url="'.url('/songs').'">
                                                                            <img src="'. asset('images/svg/play.svg').'" alt="play icone">
                                                                        </div>
                                                                        <div class="slider_ctext slider_artist_text">
                                                                        <a class="slider_ctitle slider_artist_ttl limited_text_line getAjaxRecord" data-type="audio" data-url="'.url('audio/single/'.$audio->id.'/'.$audio->audio_slug).'" href="javascript:void(0)">'.$audio->audio_title.'</a>
                                                                            <p class="slider_cdescription slider_artist_des limited_text_line">'.($artist_name != '' ? rtrim($artist_name,',') : 'Unknown').'</p>
                                                                        </div>
                                                                    </div>
                                                                </div> ';                                                                
                                                        }
                                                    ?>
                                                                   
                                                </div>
                                            </div>
                                        </div>                                            
                                            <script>
                                                var c = parseInt(`{{$c}}`)+3;
                                                for (let i = 3; i < c; i++) {
                                                    var swiper = new Swiper('.also_like_slider'+i+' .swiper-container', {
                                                        slidesPerView: 4,
                                                        spaceBetween: 30,
                                                        loop: true,
                                                        speed: 1500,
                                                        navigation: {
                                                            nextEl: '.swiper-button-next'+i,
                                                            prevEl: '.swiper-button-prev'+i,
                                                        },
                                                        breakpoints: {
                                                            1800: {
                                                                slidesPerView: 3,
                                                            },
                                                            1600: {
                                                                slidesPerView: 3,
                                                                spaceBetween: 20,
                                                            },
                                                            1500: {
                                                                slidesPerView: 2,
                                                            },
                                                            1399: {
                                                                slidesPerView: 4,
                                                                spaceBetween: 10,
                                                            },
                                                            1024: {
                                                                slidesPerView: 3,
                                                                spaceBetween: 10,
                                                            },
                                                            992: {
                                                                slidesPerView: 4,
                                                                spaceBetween: 10,
                                                            },
                                                            800: {
                                                                slidesPerView: 3,
                                                                spaceBetween: 10,
                                                            },
                                                            700: {
                                                                slidesPerView: 2,
                                                                spaceBetween: 15,
                                                            },
                                                            480: {
                                                                slidesPerView: 1,
                                                            }
                                                        },
                                                    });
                                                }
                                            </script>
                                            
                                    @else
                                        <div class="ms_empty_data">
                                            <p>{{ __("frontWords.no_track") }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach                                    
                        
                    @endif
                    
                    <!-- You may also like section -->
                    <div class="ms_artist_slider also_like_slider">
                        <div class="slider_heading_wrap">
                            <div class="slider_cheading">
                                <h4 class="cheading_title">{{ __("frontWords.trending").' '.__("frontWords.track") }} &nbsp;</h4>
                            </div>
                            <!-- Add Arrows -->
                            <div class="slider_cmn_controls">
                                <div class="slider_cmn_nav "><span class="swiper-button-next1 slider_nav_next"></span></div>
                                <div class="slider_cmn_nav"><span class="swiper-button-prev1 slider_nav_prev"></span></div>
                            </div>
                        </div>
                        @php 
                            if(isset($trending_audio) && sizeof($trending_audio) > 0){
                        @endphp                            
                        <div class="ms_artist_innerslider">
                            <div class="swiper-container">
                                <div class="swiper-wrapper">
                                    @php 
                                        foreach($trending_audio as $audio){
                                            $getArtist = json_decode($audio->artist_id);
                                            $artist_name = '';
                                            foreach($getArtist as $artistName){
                                                $artists = select(['column'=>'artist_name','table'=>'artists','where'=>['id'=>$artistName] ]);
                                                if(count($artists) > 0){
                                                    $artist_name .= $artists[0]->artist_name.',';
                                                }
                                            }
                                            if($audio->image != '' && file_exists(public_path('images/audio/thumb/'.$audio->image))){
                                                $img = '<img src="'.asset('images/audio/thumb/'.$audio['image']).'" alt="">';
                                            }else{
                                                $img = '<img src="'.dummyImage('audio').'" alt="" class="img-fluid">';
                                            }
                                            echo'<div class="swiper-slide play_btn">
                                                    <div class="slider_cbox slider_artist_box text-center play_box_container">
                                                        <div class="slider_cimgbox slider_artist_imgbox play_box_img">'.$img.'</div>
                                                        <div class="ms_play_icon play_music" data-musicid="'.$audio->id.'" data-musictype="audio" data-url="'.url('/songs').'">
                                                            <img src="'. asset('images/svg/play.svg').'" alt="play icone">
                                                        </div>
                                                        <div class="slider_ctext slider_artist_text">
                                                            <a class="slider_ctitle slider_artist_ttl limited_text_line getAjaxRecord" data-type="audio" data-url="'.url('audio/single/'.$audio['id'].'/'.$audio['audio_slug']).'" href="javascript:void(0)">'.$audio['audio_title'].'</a>          
                                                        </div>
                                                    </div>
                                                </div>';
                                        }
                                    @endphp                                      
                                                        
                                </div>
                            </div>
                        </div>
                        @php
                            }else{
                                echo '<div class="ms_empty_data">
                                        <p>'.__("frontWords.no_track").'</p>
                                    </div>';
                            }
                        @endphp
                    </div>

                    <!-- You may also like section -->
                    <div class="ms_artist_slider also_like_slider2">
                        <div class="slider_heading_wrap">
                            <div class="slider_cheading">
                                <h4 class="cheading_title">{{ __('adminWords.all').' '.__('frontWords.track') }} &nbsp;</h4>
                            </div>
                            <!-- Add Arrows -->
                            <div class="slider_cmn_controls">
                                <div class="slider_cmn_nav "><span class="swiper-button-next2 slider_nav_next"></span></div>
                                <div class="slider_cmn_nav"><span class="swiper-button-prev2 slider_nav_prev"></span></div>
                            </div>
                        </div>
                        @php
                            if(sizeof($all_audios) > 0){
                        @endphp
                        <div class="ms_artist_innerslider">
                            <div class="swiper-container">
                                <div class="swiper-wrapper">
                                    <?php
                                        foreach($all_audios as $audio){
                                            $getArtist = json_decode($audio->artist_id);
                                            $artist_name = '';
                                            foreach($getArtist as $artistName){
                                                $artists = select(['column'=>'artist_name','table'=>'artists','where'=>['id'=>$artistName] ]);
                                                if(count($artists) > 0){
                                                    $artist_name .= $artists[0]->artist_name.',';
                                                }
                                            }
                                            $getLikeDislikeAudio = getFavDataId(['column' => 'audio_id', 'audio_id' => $audio->id]);
                                            $download = '';
                                            if(!empty($userPlan) && $userPlan->is_download == 1){
                                                $download = '<li>'.($audio->aws_upload == 1 ? '<a href="'.getSongAWSUrlHtml($audio).'"><span class="opt_icon"><span class="icon icon_dwn"></span></span>'.__("frontWords.download_now").'</a>' : '<a href="javascript:;" class="download_track" data-musicid="'.$audio->id.'"><span class="opt_icon"><span class="icon icon_dwn"></span></span>'.__("frontWords.download_now").'</a>').'</li>';
                                            }
                                            if($audio->image != '' && file_exists(public_path('images/audio/thumb/'.$audio->image))){
                                                $img = '<img src="'.asset('images/audio/thumb/'.$audio->image).'" alt="" class="img-fluid">';
                                            }else{
                                                $img = '<img src="'.dummyImage('audio').'" alt="" class="img-fluid">';
                                            }
                                            echo'<div class="swiper-slide play_btn play_icon_btn">
                                                    <div class="slider_cbox slider_artist_box text-center play_box_container">
                                                        <div class="slider_cimgbox slider_artist_imgbox play_box_img">'.$img.'</div>
                                                        <div class="ms_play_icon play_music" data-musicid="'.$audio->id.'" data-musictype="audio" data-url="'.url('/songs').'">
                                                            <img src="'. asset('images/svg/play.svg').'" alt="play icone">
                                                        </div>
                                                        <div class="slider_ctext slider_artist_text">
                                                            <a class="slider_ctitle slider_artist_ttl limited_text_line getAjaxRecord" data-type="audio" data-url="'.url('audio/single/'.$audio->id.'/'.$audio->audio_slug).'" href="javascript:void(0)">'.$audio->audio_title.'</a>
                                                            <p class="slider_cdescription slider_artist_des limited_text_line">'.($artist_name != '' ? rtrim($artist_name,',') : 'Unknown').'</p>
                                                        </div>
                                                    </div>
                                                </div> ';
                                        }
                                    ?>
                                                   
                                </div>
                            </div>
                        </div>
                        @php
                            }else{
                                echo '<div class="ms_empty_data">
                                        <p>'.__("frontWords.no_track").'</p>
                                    </div>';
                            }
                        @endphp
                    </div>                    
                    

                </div>
            </div>
        </div>
    </div>

   @include('layouts.front.footer')